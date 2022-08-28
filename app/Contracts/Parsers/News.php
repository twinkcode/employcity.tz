<?php

namespace App\Contracts\Parsers;

interface News
{
    public function xPaths(): array;

    public function parseTopNews($count = 15): array;

    public function parseSingleNews($item): array;

    public function parseListNews(): array;

    public function getElements(string $variant = 'default', \DOMXpath $xpath): array;

    public function news($publish_date);

    public function index($service);
}
