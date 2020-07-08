<?php

namespace App\Repositories;

use App\Exceptions\ProductNotFoundException;
use App\Product;
use Illuminate\Support\Arr;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductsRepository
{
    /**
     * @param array $data
     * @return Product|bool
     * @throws ProductNotFoundException
     */
    public function createOrUpdate(array $data)
    {
        if ($validated = $this->validate($data)) {
            if (!empty($data['id'])) {
                /** @var Product $product */
                $product = Product::find($data['id']);

                if (is_null($product)) {
                    throw new ProductNotFoundException("Product with ID {$data['id']} NOT FOUND.");
                }

                $product->update($validated);
            }
            else {
                $product = Product::create($validated);
            }

            if (!empty($data['attributes'])) {
                $product->updateAttributes($data['attributes']);
            }

            return $product;
        }

        return false;
    }

    /**
     * @param iterable $products
     * @throws ProductNotFoundException
     */
    public function bulkCreateOrUpdate(iterable $products)
    {
        foreach ($products as $product) {
            $this->createOrUpdate($product);
        }
    }

    /**
     * @param array $data
     * @return array|bool
     */
    private function validate(array $data)
    {
        if (empty($data['name']) || empty($data['category_id'])) {
            return false;
        }

        return [
            'name' => Arr::get($data, 'name'),
            'category_id' => (int)Arr::get($data, 'category_id', null),
            'brand_id' => (int)Arr::get($data, 'brand_id', null),
            'manufacturer_id' => Arr::get($data, 'manufacturer_id', null),
            'description' => Arr::get($data, 'description', null)
        ];
    }
}
