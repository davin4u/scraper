<?php

namespace App\Repositories;

use App\ApiResponse;
use App\Brand;
use App\Category;
use App\Domain;
use App\Exceptions\BrandNotFoundException;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DomainNotFoundException;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
     * @throws \Exception
     */
    public function createOrUpdate($data)
    {
        /** @var ApiResponse $response */
        $response = api()->get(
            'products',
            Arr::only($data, ['domain_id', 'sku']),
            'search-one');

        if (!is_null($response) && !$response->withErrors()) {
            $productData = $response->data();

            if (!is_null($productData)) {
                return ! is_null(
                    api()->update('products', (int) $productData['id'], $data)
                );
            }
        }
        else {
            $created = api()->store('products', $data);

            if (!is_null($created)) {
                return true;
            }
        }

        Log::error("Product create/update error.");
        Log::error($data);
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
