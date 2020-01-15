<?php

namespace App\Repositories;

use App\ProductAttributes;

/**
 * Class ProductAttributesRepository
 * @package App\Repositories
 */
class ProductAttributesRepository
{
    /**
     * @param string $name
     * @param int $categoryId
     * @return ProductAttributes|mixed
     */
    public function recognizeAttribute(string $name, int $categoryId)
    {
        $model = $this->find($name, $categoryId);

        if (is_null($model)) {
            $model = $this->create($name, $categoryId);

            $model->generateUniqueAttributeKey();
        }

        return $model;
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return mixed
     */
    public function find(string $name, int $categoryId)
    {
        return ProductAttributes::where('category_id', $categoryId)->where('name', $name)->first();
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return ProductAttributes
     */
    public function create(string $name, int $categoryId)
    {
        $model = ProductAttributes::create([
            'name' => $name,
            'category_id' => $categoryId
        ]);

        return $model;
    }
}
