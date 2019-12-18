<?php

namespace App\Parsers\Helpers;

/**
 * Class CategoryMatcher
 * @package App\Parsers\Helpers
 */
class CategoryMatcher
{
    /**
     * @var array
     */
    protected static $map = [
        1 => [
            'ноутбуки'
        ]
    ];

    /**
     * @param $name
     * @return int|string|null
     */
    public static function match($name)
    {
        foreach (static::$map as $categoryId => $map) {
            if (in_array(strtolower($name), $map)) {
                return $categoryId;
            }
        }

        return null;
    }
}
