<?php

namespace App\Services\Parsers;

class FakeNews
{
    public string $url;

    public function __construct()
    {
        $this->url = fake()->title();
    }
}
