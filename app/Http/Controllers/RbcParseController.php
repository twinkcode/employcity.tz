<?php

namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise\Promise;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RbcParseController extends Controller
{
    public $xPathSamples;

    public function __construct()
    {
        $this->xPathSamples = [
            'default' => [
                'signatureUrlPart' => 'https://www.rbc.ru/',
                'context' => '//*[contains(@class, "article__content")]',
                'title' => '//h1',
                'subtitle' => '//*[@class="article__text__overview"]',
                'textsEls' => '//*[contains(@class, "article__content")]//p',
                'imgPrefix' => '',
                ////div[contains(@class, "article__main-image__wrap")]//img |
                'img' => '//img[contains(@class, "article__main-image__image")] | //img[contains(@class, "article__inline-item__image")]',
            ],
            'unusual' => [
                'signatureUrlPart' => 'https://20idei.rbc.ru/',
                'context' => '//main[contains(@class, "article__main")]',
                'title' => '//h2[contains(@class, "article__title")]',
                'subtitle' => '//*[contains(@class, "article__header-right")]/p',
                'textsEls' => '//main//*[contains(@class, "article__content")]/*[not(contains(@class, "read-also")) and not(self::figure)]',
                'imgPrefix' => 'https://20idei.rbc.ru',
                'img' => '//main[contains(@class, "article__main")]//figure//img[1]',
            ],
        ];
    }


    public function index(){
        $news = News::query()
            ->orderBy('publish_date','desc')
//            ->take(2)
            ->get()
        ;
        return view('home')->with('news',$news);
    }

    public function news($publish_date){
        $new = News::query()
            ->where('publish_date',$publish_date)
            ->get()
            ->first()
        ;
        return view('news')->with('new', $new);
    }

    public function conv(){
        $news = News::query()
            ->orderBy('publish_date','desc')
            ->get(['texts'])
            ->first();
        $m = implode(' \n', $news['texts']);
        $scr = <<<SCR
            <div id="output" style="white-space: pre-wrap;"></div>
            <script>
                document.getElementById('output').innerHTML = "$m";
            </script>
        SCR;
        echo $scr;
    }

    public function currentUrl(): string
    {
        $request = request()->all();
        list($count, $city, $timestamp) = [
            $request['count'] ? ($request['count'] >= 10 ? $request['count'] : 10) : 15,
            $request['city'] ?? 'moscow',
            $request['timestamp'] ?? Carbon::now()->timestamp,
        ];
        return $request->url ?? "https://rbc.ru/v10/ajax/get-news-feed/project/rbcnews.$city/lastDate/$timestamp/limit/$count?_=$timestamp";
    }

    public function clean($html)
    {
        $patterns = ['/\n|\r/', '/\s\s+/'];
        $replacements = ['', ' '];
        return trim(preg_replace($patterns, $replacements, $html));
    }

    public function getElements(string $variant, \DOMXpath $xpath)
    {

        $xp = $this->xPathSamples[$variant];
        $context = $xpath->query($xp['context'])->item(0);

        $title = $this->clean($xpath->query($xp['title'], $context)->item(0)->textContent);

        $subtitle = $xpath->query($xp['subtitle'], $context);
        $subtitle = $subtitle->length ? $this->clean($subtitle->item(0)->nodeValue) : null;

        $textsEls = $xpath->query($xp['textsEls'], $context);
        $texts = [];
        foreach ($textsEls as $el) {
            $node = $el->firstChild;
            if ($node) {
                do  $texts[] = $this->clean($node->textContent);
                while ($node = $node->nextSibling);
            }
        }

        $imSrc = $xpath->query($xp['img'], $context)->item(0);
        $imSrc = $imSrc ? $xp['imgPrefix'] . trim($imSrc->getAttribute('src'), '.') : null;;

        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'img' => $imSrc,
            'texts' => $texts,
        ];
    }


    public function parseSingleNews($item): array
    {
        $link = $item['link'];
        $html = Http::get($link)->body();

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_use_internal_errors($internalErrors);
        $xpath = new \DOMXpath($doc);

        if (str_contains($link, $this->xPathSamples['unusual']['signatureUrlPart']))
            $out = $this->getElements('unusual', $xpath);
        else $out = $this->getElements('default', $xpath);

        $record = [
            'title' => $out['title'] ?? null,
            'subtitle' => $out['subtitle'] ?? null,
            'img' => $out['img'] ?? null,
            'publish_date' => $item['publish_date'],
            'link' => $item['link'],
            'texts' => $out['texts'] ?? null,
        ];

        News::query()->updateOrCreate([
            'publish_date' => $record['publish_date'],
            'link' => $record['link'],
        ], $record);

        return $record;
    }

    public function parseListNews(): array
    {
        $list = $this->parseTopNews();
        $newsList = [];
        foreach ($list as $item) {
            $newsList[] = $this->parseSingleNews($item);
        }
        return $newsList;
    }


    public function parseTopNews($count = 15): array
    {

        $html = Http::get($this->currentUrl())->body();

        $items = json_decode($html)->items;
        $alreadyItems = News::query()
            ->orderBy('publish_date', 'DESC')
            ->take($count)
            ->get(['publish_date'])
            ->pluck('publish_date')
            ->toArray();

        $out = array_map(function ($item) use ($alreadyItems) {
            if (!in_array($item->publish_date_t, $alreadyItems)) {
                $item = [
//                  'publish_date' => Carbon::createFromTimestamp($item->publish_date_t)->toDateTimeString(),
                    'publish_date' => $item->publish_date_t,
                    'link' => preg_replace('/(.*)a href="(.*?)"(.*)/', '$2', $this->clean($item->html)),
                ];
                News::query()->updateOrCreate(['publish_date' => $item['publish_date']], $item);
                return $item;
            }
        }, $items);
        $out = array_filter($out);
        $out = array_slice($out, 0, $count, true);

        return $out;
    }

    /** This method parse html and return less than 15 items (more news loading by ajax - I'll use $this->parseTopNews)
     * living here for fauteres expand parsers
     * todo: abandoned code. If there is a need for it, put it in order
     * @return array
     */
    public function parseTopNewsDOM()
    {

        $html = Http::get('https://rbc.ru')->body();

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_use_internal_errors($internalErrors);
        $xpath = new \DOMXpath($doc);

        $first15elemsXpathQuery = '//*[@class="js-news-feed-list"]/a[contains(@class, "news-feed__item") and not(contains(@class, "lib-vertical-card"))][position() < 26]';
        $elements = $xpath->query($first15elemsXpathQuery);
        //can parse short titles (no present in ajax)
//        $elementsTitles = $xpath->query($first15elemsXpathQuery.'//span[contains(@class, "news-feed__item__title")]');
        $out = [];
        if (!is_null($elements)) {
            foreach ($elements as $key => $element) {
                $out[$key]['link'] = $element->getAttribute('href');
                $out[$key]['publish_date'] = Carbon::createFromTimestamp($element->getAttribute('data-modif'))->toDateTimeString();
//                $title = $elementsTitles->item($key)->nodeValue ?? null;
//                $out[$key]['title_short']= $title ? $this->clean($title) : null;
            }
        }
        return $out;
    }

}
