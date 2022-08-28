<?php

namespace App\Http\Controllers;

use App\Contracts\Parsers\News;

class NewsParseController extends Controller
{

    public function index(News $service)
    {
        return view('home', ['news' => $service->index()]);
    }

    public function news(News $service, $publish_date)
    {
        return view('news')->with('new', $service->news($publish_date));
    }

    public function parseListNews(News $service)
    {
        return $service->parseListNews();
    }

    public function purgeNoTexts(News $service)
    {
        return $service->purgeNoTexts();
    }

}
