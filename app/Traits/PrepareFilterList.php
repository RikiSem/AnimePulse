<?php

namespace App\Traits;

trait PrepareFilterList
{
    public static function prepareList(array $array, string $title): array
    {
        $result = array_map(function ($item) {
            return [
                'title' => $item['title'] ?? $item,
                'value' => $item['value'] ?? $item
            ];
        }, $array);
        array_unshift($result, [
            'title' => $title,
            'value' => '',
        ]);

        return $result;
    }
}
