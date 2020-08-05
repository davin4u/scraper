<?php

namespace App\Repositories;

use App\Exceptions\ProductNotFoundException;
use App\StoreProduct;
use App\StoreProductDetails;
use Illuminate\Support\Arr;

/**
 * Class StoreProductsRepository
 * @package App\Repositories
 */
class StoreProductsRepository
{
    /**
     * @param array $data
     * @return StoreProduct|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     * @throws ProductNotFoundException
     * @throws \InvalidArgumentException
     */
    public function createOrUpdate(array $data)
    {
        /** @var StoreProduct $storeProduct */
        $storeProduct = null;

        if ($validated = $this->validate($data)) {
            if (!empty($validated['id'])) {
                $storeProduct = $this->find($validated['id']);

                if (!is_null($storeProduct)) {
                    $storeProduct->updateDetails($validated);

                    return $storeProduct;
                }

                throw new ProductNotFoundException("StoreProduct not found.");
            }
            else if (!empty($validated['sku'])) {
                $storeProduct = $this->find($validated['sku']);

                if (!is_null($storeProduct)) {
                    $storeProduct->updateDetails($validated);

                    return $storeProduct;
                }
            }

            /*
             * We create StoreProduct with product_id = 0 to indicate that
             * it requires moderation, we can't automatically match it with our products table
             * so we put 0 and we match it manually later
             */
            $storeProduct = StoreProduct::create([
                'store_id' => $data['store_id'],
                'product_id' => 0
            ]);

            $storeProduct->updateDetails($validated);
        }

        return $storeProduct;
    }

    /**
     * @param $idOrSku
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function find($idOrSku)
    {
        if (is_string($idOrSku)) {
            return $this->findBySku($idOrSku);
        }

        if (is_integer($idOrSku)) {
            return $this->findById($idOrSku);
        }

        return null;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|object|null
     */
    public function findById(int $id)
    {
        return StoreProduct::find($id);
    }

    /**
     * @param string $sku
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function findBySku(string $sku)
    {
        if ($details = StoreProductDetails::where('sku', $sku)->first()) {
            /** @var StoreProductDetails $details */

            return $details->storeProduct()->first();
        }

        return null;
    }

    /**
     * @param array $data
     * @return array
     * @throws \InvalidArgumentException
     */
    private function validate(array $data): array
    {
        $data = array_filter($data, function ($item) {
            return !is_null($item) && !empty($item);
        });

        if (empty($data['store_id'])) {
            throw new \InvalidArgumentException("Property store_id is required.");
        }

        if (empty($data['name'])) {
            throw new \InvalidArgumentException("Property name is required.");
        }

        if (empty($data['category_id'])) {
            throw new \InvalidArgumentException("Property category_id is required.");
        }

        return [
            //'store_id' => (int)Arr::get($data, 'store_id'),
            'sku' => Arr::get($data, 'sku', null),
            'url' => Arr::get($data, 'url', null),
            'name' => Arr::get($data, 'name', null),
            //'brand_id' => Arr::get($data, 'brand_id', null),
            //'category_id' => (int)Arr::get($data, 'category_id'),
            'description' => Arr::get($data, 'description', null),
            'price' => (float)Arr::get($data, 'price', 0),
            'old_price' => (float)Arr::get($data, 'old_price', 0),
            'currency' => (string)Arr::get($data, 'currency', null),
            'is_available' => (int)Arr::get($data, 'is_available', 1),
            'delivery_text' => Arr::get($data, 'delivery_text', null),
            'delivery_days' => Arr::get($data, 'delivery_days', null),
            'delivery_price' => Arr::get($data, 'delivery_price', null),
            'benefits' => Arr::get($data, 'benefits', null),
            'meta_title' => Arr::get($data, 'meta_title', null),
            'meta_description' => Arr::get($data, 'meta_description', null),
            'meta_keywords' => Arr::get($data, 'meta_keywords', null)
        ];
    }
}