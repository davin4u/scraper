<?php

namespace App\Repositories;

use App\Domain;
use App\Exceptions\DomainNotFoundException;
use App\Product;
use Illuminate\Support\Arr;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductsRepository
{
    /**
     * @var null
     */
    protected $domain = null;

    /**
     * @param $sku
     * @return mixed
     */
    public function find($sku)
    {
        $query = Product::query();

        if (!is_null($this->domain)) {
            $query->where('domain_id', $this->domain);
        }

        $product = $query->where('sku', $sku)->get();

        if (!is_null($this->domain) && $product->count() > 1) {
            // @TODO in case if several products were found with the same SKU for the same domain - notify admin
        }

        return $product->first();
    }

    /**
     * @param $data
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createOrUpdate($data)
    {
        if ($product = $this->find(Arr::get($data, 'sku', null))) {
            /** @var Product $product */

            $product->update(Arr::except($data, ['price', 'domain_id', 'currency', 'old_price', 'city_id', 'store_id']));

            $product->syncPrice(Arr::only($data, ['price', 'currency', 'old_price', 'city_id', 'store_id']));

            $product->saveStorableDocument(Arr::get($data, 'attributes', []));

            return true;
        }

        /** @var Product $product */
        $product = Product::create(Arr::except($data, ['price', 'currency', 'old_price', 'city_id', 'store_id']));

        $product->syncPrice(Arr::only($data, ['price', 'currency', 'old_price', 'city_id', 'store_id']));

        $product->saveStorableDocument(Arr::get($data, 'attributes', []));

        return true;
    }

    /**
     * @param iterable $products
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function bulkCreateOrUpdate(iterable $products)
    {
        foreach ($products as $product) {
            $this->createOrUpdate($product);
        }
    }

    /**
     * @param $nameOrId
     * @return $this
     * @throws DomainNotFoundException
     */
    public function domain($nameOrId)
    {
        if (is_integer($nameOrId)) {
            $this->domain = $nameOrId;

            return $this;
        }

        if (is_string($nameOrId)) {
            $domain = Domain::where('name', $nameOrId)->first();

            if (!is_null($domain)) {
                $this->domain = $domain->id;

                return $this;
            }
        }

        throw new DomainNotFoundException("Domain [$nameOrId] NOT FOUND.");
    }
}
