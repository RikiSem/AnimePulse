<?php

namespace App\Traits;

trait ClearDescription
{
    public static function clearDescription(string $description): string
    {
        $result = str_replace('\n', ' ', preg_replace('/\[character=[^\]]+](.*?)\[\/character]/u', '$1', $description));
        $result = preg_replace('/\[anime=.*?\]/', '', $result);
        $result = preg_replace('/\[\/anime\]/', '', $result);
        $result = preg_replace('/\[\[(.*?)\]\]/', '$1', $result);
        return $result;
    }
}
