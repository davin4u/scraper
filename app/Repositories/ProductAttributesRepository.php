<?php

namespace App\Repositories;

use App\Attribute;
use App\Crawler\Matchers\SimpleAttributeMatcher;

/**
 * Class ProductAttributesRepository
 * @package App\Repositories
 */
class ProductAttributesRepository
{
    /**
     * @var SimpleAttributeMatcher
     */
    private static $attributeMatcher = null;

    /**
     * ProductAttributesRepository constructor.
     */
    public function __construct()
    {
        if (is_null(static::$attributeMatcher)) {
            static::$attributeMatcher = new SimpleAttributeMatcher();
        }
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return int
     * @throws \Exception
     */
    public function recognizeAttribute(string $name, int $categoryId)
    {
        return static::$attributeMatcher->match($name, ['category_id' => $categoryId], true);
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return mixed
     */
    public function find(string $name, int $categoryId)
    {
        return Attribute::where('category_id', $categoryId)->where('name', $name)->first();
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return Attribute
     */
    public function create(string $name, int $categoryId)
    {
        $model = Attribute::create([
            'name' => $name,
            'category_id' => $categoryId
        ]);

        return $model;
    }

    /**
     * @param int $categoryId
     * @param array $fillValues
     * @return array
     */
    public function getCategoryAttributes(int $categoryId, $fillValues = [])
    {
        $prepared = [];

        /** @var \Illuminate\Database\Eloquent\Collection $attributes */
        $attributes = Attribute::where('category_id', $categoryId)->get();

        if ($attributes->count()) {
            foreach ($attributes as $attr) {
                $prepared[] = [
                    'attribute_key' => $attr->attribute_key,
                    'name' => $attr->name,
                    'value' => isset($fillValues[$attr->attribute_key]) ? $fillValues[$attr->attribute_key] : null
                ];
            }
        }

        return $prepared;
    }
}
