<?php

namespace App\Parsers\Helpers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SimpleMatcher
 * @package App\Parsers\Helpers
 */
class SimpleMatcher
{
    /**
     * @var array
     */
    protected static $map;

    /**
     * SimpleCategoryMatcher constructor.
     */
    public function __construct()
    {
        static::loadMapping();
    }

    /**
     * @param string $name
     * @return int
     * @throws \Exception
     */
    public function match(string $name) : int
    {
        foreach (static::$map as $categoryId => $map) {
            if (in_array($name, $map) || in_array(mb_strtolower($name), $map)) {
                return (int)$categoryId;
            }
        }

        // create entity if not found
        // @TODO remove or change below logic after initial scraping
        $created = static::$model::create([
            'name' => $name,
            'map' => [$name]
        ]);

        static::loadMapping();

        return $created->id;
    }

    protected static function loadMapping()
    {
        static::$map = [];

        if (!static::$model) {
            throw new \Exception("Property model should be set in child matcher class.");
        }

        $categories = (new static::$model)->query()->get();

        foreach ($categories as $category) {
            static::$map[$category->id] = $category->map;
        }
    }
}
