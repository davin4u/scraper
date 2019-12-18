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
     * @var Product
     */
    protected $query;

    /**
     * @var null
     */
    protected $domain = null;

    /**
     * ProductsRepository constructor.
     */
    public function __construct()
    {
        $this->query = Product::query();
    }

    /**
     * @param $sku
     * @return mixed
     */
    public function find($sku)
    {
        $product = $this->query->where('sku', $sku)->get();

        if (!is_null($this->domain) && $product->count() > 1) {
            // @TODO in case if several products were found with the same SKU for the same domain - notify admin
        }

        return $product->first();
    }

    /**
     * @param $data
     * @return bool
     */
    public function createOrUpdate($data)
    {
        if ($product = $this->find(Arr::get($data, 'sku', null))) {
            /** @var Product $product */

            $product->update(Arr::except($data, ['price', 'domain_id']));

            return true;
        }

        Product::create(Arr::except($data, ['price']));

        return true;
    }

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
            $this->query = $this->query->where('domain_id', $nameOrId);

            $this->domain = $nameOrId;

            return $this;
        }

        if (is_string($nameOrId)) {
            $domain = Domain::where('name', $nameOrId)->first();

            if (!is_null($domain)) {
                $this->query = $this->query->where('domain_id', $domain->id);

                $this->domain = $domain->id;

                return $this;
            }
        }

        throw new DomainNotFoundException("Domain [$nameOrId] NOT FOUND.");
    }
}
