<?php

namespace App\Services\Parsers;

use App\Contracts\Parsers\News;
use App\Models\News as NewsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Rbk implements News
{
    public function xPaths(): array
    {
        return [
            'default' => [
                'signatureUrlPart' => 'https://www.rbc.ru/',
                'context' => '//*[contains(@class, "article__content")]',
                'title' => '//h1',
                'subtitle' => '//*[@class="article__text__overview"]',
                'textsEls' => '//*[contains(@class, "article__content")]//p',
                'imgPrefix' => '',
                'img' => '(//div[contains(@class, "article__main-image__wrap")]//img)[1] | //img[contains(@class, "article__main-image__image")] | //img[contains(@class, "article__inline-item__image")]',
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

    public function parseTopNews(int $count = 15): array
    {
        $html = Http::get($this->currentUrl())->body();

        $items = json_decode($html)->items;
        $alreadyItems = NewsModel::query()
            ->orderBy('publish_date', 'DESC')
            ->take($count)
            ->get(['publish_date'])
            ->pluck('publish_date')
            ->toArray();

        $out = array_map(function ($item) use ($alreadyItems) {
            if (!in_array($item->publish_date_t, $alreadyItems)) { //filter already present
                $item = [
//                  'publish_date' => Carbon::createFromTimestamp($item->publish_date_t)->toDateTimeString(),
                    'publish_date' => $item->publish_date_t,
                    'link' => preg_replace('/(.*)a href="(.*?)"(.*)/', '$2', clean($item->html)),
                ];
                NewsModel::query()->updateOrCreate(['publish_date' => $item['publish_date']], $item);
                return $item;
            }
        }, $items);
        $out = array_filter($out);
        return array_slice($out, 0, $count, true);
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

        if (str_contains($link, $this->xPaths()['unusual']['signatureUrlPart']))
            $out = $this->getElements( $xpath, 'unusual');
        else $out = $this->getElements( $xpath, 'default');

        $record = [
            'title' => $out['title'] ?? null,
            'subtitle' => $out['subtitle'] ?? null,
            'img' => $out['img'] ?? null,
            'publish_date' => $item['publish_date'],
            'link' => $item['link'],
            'texts' => $out['texts'] ?? null,
        ];

        if (count($record['texts']) > 0) {
            NewsModel::query()
                ->updateOrCreate([
                    'publish_date' => $record['publish_date'],
                    'link' => $record['link'],
                ], $record);
        } else {
            // delete record, if no texts. because something wrong
            $message = NewsModel::query()
                ->where('publish_date', $record['publish_date'])
                ->where('link', $record['link'])
                ->delete();
            Log::warning('delete record because NO TEXTs: ', ['message'=>$message]);
        }
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

    public function getElements(\DOMXpath $xpath, string $variant = 'default'): array
    {
        $xp = $this->xPaths()[$variant];
        $context = $xpath->query($xp['context'])->item(0);

        $title = clean($xpath->query($xp['title'], $context)->item(0)->textContent);

        $subtitle = $xpath->query($xp['subtitle'], $context);
        $subtitle = $subtitle->length ? clean($subtitle->item(0)->nodeValue) : null;

        $textsEls = $xpath->query($xp['textsEls'], $context);
        $texts = [];
        foreach ($textsEls as $el) {
            $node = $el->firstChild;
            if ($node) {
                do  $texts[] = clean($node->textContent);
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

    public function news($publish_date)
    {
        $new = NewsModel::query()
            ->where('publish_date', $publish_date)
            ->get()
            ->first();
        return $new;
        return view('news')->with('new', $new);
    }

    public function index()
    {
        return NewsModel::query()
            ->orderBy('publish_date', 'desc')
            ->get();
    }

    public function currentUrl()
    {
        $request = request()->all();
        list($count, $city, $timestamp) = [
            $request['count'] ? ($request['count'] >= 10 ? $request['count'] : 10) : 15,
            $request['city'] ?? 'moscow',
            $request['timestamp'] ?? Carbon::now()->timestamp,
        ];
        return $request->url ?? "https://rbc.ru/v10/ajax/get-news-feed/project/rbcnews.$city/lastDate/$timestamp/limit/$count?_=$timestamp";
    }

    /** optional function for destroy rows with no texts
     * @param NewsModel $news
     * @return mixed
     */
    public function purgeNoTexts(){
        $report = NewsModel::query()
            ->whereJsonLength('texts', 0)
            ->orWhereNull('texts')
            ->delete();
        Log::emergency('Force delete all records because NO TEXTs: ', ['model'=>get_class(new NewsModel),'status' => $report]);
        return $report;
    }

}
