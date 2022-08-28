<?php

function clean(string $text): string
{
    $patterns = ['/\n|\r/', '/\s\s+/'];
    $replacements = ['', ' '];
    return trim(preg_replace($patterns, $replacements, $text));
}
