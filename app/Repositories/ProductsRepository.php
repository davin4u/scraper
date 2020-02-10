<?php

namespace App\Repositories;

use App\Brand;
use App\Category;
use App\Domain;
use App\Exceptions\BrandNotFoundException;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DomainNotFoundException;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductsRepository extends EloquentRepository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Product::class;
    }

    /**
     * @param $sku
     * @return mixed
     */
    public function find($sku)
    {
        /** @var Collection $product */
        $product = $this->where('sku', '=', $sku)->get();

        if ($product->count() > 1) {
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

            $product->fillStorableDocument(Arr::only($data, ['attributes', 'images'], []))
                    ->saveStorableDocument();

            return true;
        }

        /** @var Product $product */
        $product = Product::create(Arr::except($data, ['price', 'currency', 'old_price', 'city_id', 'store_id']));

        $product->syncPrice(Arr::only($data, ['price', 'currency', 'old_price', 'city_id', 'store_id']));

        $product->fillStorableDocument(Arr::only($data, ['attributes', 'images'], []))
                ->saveStorableDocument();

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
     * @return ProductsRepository
     * @throws DomainNotFoundException
     */
    public function domain($nameOrId)
    {
        if (is_integer($nameOrId)) {
            $this->where('domain_id', '=', $nameOrId);

            return $this;
        }

        if (is_string($nameOrId)) {
            $domain = Domain::where('name', $nameOrId)->first();

            if (!is_null($domain)) {
                $this->where('domain_id', '=', $domain->id);

                return $this;
            }
        }

        throw new DomainNotFoundException("Domain [$nameOrId] NOT FOUND.");
    }

    /**
     * @param $nameOrId
     * @return ProductsRepository
     * @throws CategoryNotFoundException
     */
    public function category($nameOrId)
    {
        if (is_integer($nameOrId)) {
            $this->where('category_id', '=', $nameOrId);

            return $this;
        }

        if (is_string($nameOrId)) {
            $category = Category::where('name', $nameOrId)->first();

            if (!is_null($category)) {
                $this->where('category_id', '=', $category->id);

                return $this;
            }
        }

        throw new CategoryNotFoundException("Category $nameOrId NOT FOUND.");
    }

    /**
     * @param $nameOrId
     * @return ProductsRepository
     * @throws BrandNotFoundException
     */
    public function brand($nameOrId)
    {
        if (is_integer($nameOrId)) {
            $this->where('brand_id', '=', $nameOrId);

            return $this;
        }

        if (is_string($nameOrId)) {
            $brand = Brand::where('name', $nameOrId)->first();

            if (!is_null($brand)) {
                $this->where('brand_id', '=', $brand->id);

                return $this;
            }
        }

        throw new BrandNotFoundException("Brand $nameOrId NOT FOUND.");
    }
}
