<?php


/** Clear text from hyphenation and double spaces
 * @param string $text
 * @return string
 */
function clean(string $text): string
{
    $patterns = ['/\n|\r/', '/\s\s+/'];
    $replacements = ['', ' '];
    return trim(preg_replace($patterns, $replacements, $text));
}
