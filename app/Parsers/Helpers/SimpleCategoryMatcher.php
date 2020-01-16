<?php

namespace App\Parsers\Helpers;

/**
 * Class SimpleCategoryMatcher
 * @package App\Parsers\Helpers
 */
class SimpleCategoryMatcher implements CategoryMatcher
{
    /**
     * @var array
     */
    protected static $map = [];

    /**
     * SimpleCategoryMatcher constructor.
     */
    public function __construct()
    {
        static::loadMapping();
    }

    /**
     * @param $name
     * @return int|null
     */
    public function match(string $name) : int
    {
        if (empty(static::$map)) {
            static::loadMapping();
        }

        foreach (static::$map as $categoryId => $map) {
            if (in_array($name, $map) || in_array(mb_strtolower($name), $map)) {
                return (int)$categoryId;
            }
        }

        return null;
    }

    protected static function loadMapping()
    {
        static::$map = [];

        $categories = \App\Category::all();

        foreach ($categories as $category) {
            static::$map[$category->id] = $category->map;
        }
    }
}
