<?php

namespace App\Services\Parsers;
use App\Contracts\Parsers\News;

class Rbk implements News
{
    public string $url;

    public function __construct()
    {
        $this->url = fake()->url();
    }


    /**
     * @return array
     */
    public function xPaths(): array
    {
        // TODO: Implement xPaths() method.
    }

    /**
     * @param int $count
     * @return array
     */
    public function parseTopNews($count = 15): array
    {
        // TODO: Implement parseTopNews() method.
    }

    /**
     * @param $item
     * @return array
     */
    public function parseSingleNews($item): array
    {
        // TODO: Implement parseSingleNews() method.
    }

    /**
     * @return array
     */
    public function parseListNews(): array
    {
        // TODO: Implement parseListNews() method.
    }

    /**
     * @param string $variant
     * @param \DOMXpath $xpath
     * @return array
     */
    public function getElements(string $variant = 'default', \DOMXpath $xpath): array
    {
        // TODO: Implement getElements() method.
    }

    /**
     * @param $publish_date
     * @return mixed
     */
    public function news($publish_date)
    {
        // TODO: Implement news() method.
    }

    /**
     * @param $service
     * @return mixed
     */
    public function index($service)
    {
        // TODO: Implement index() method.
    }
}
