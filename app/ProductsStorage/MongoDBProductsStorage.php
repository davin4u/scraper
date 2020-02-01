<?php

namespace App\ProductsStorage;

use App\Exceptions\ProductNotFoundException;
use App\ProductsStorage\Interfaces\MongoDBClientInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;
use Illuminate\Support\Collection;

/**
 * Class MongoDBProductsStorage
 * @package App\ProductsStorage
 */
class MongoDBProductsStorage implements ProductsStorageInterface
{
    /**
     * @var MongoDBClientInterface
     */
    protected $client;

    /**
     * MongoDBProductsStorage constructor.
     * @param MongoDBClientInterface $client
     */
    public function __construct(MongoDBClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $id
     * @return Interfaces\DocumentInterface|mixed
     */
    public function find($id)
    {
        try {
            return $this->client->collection(env('MONGODB_PRODUCTS_COLLECTION'))->find($id);
        }
        catch (ProductNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param array $filter
     * @param array $options
     * @return array
     */
    public function where($filter = [], array $options = []) : array
    {
        return $this->client->collection(env('MONGODB_PRODUCTS_COLLECTION'))->where($filter, $options);
    }

    /**
     * @param $id
     * @param $attributes
     * @return Interfaces\DocumentInterface|mixed
     */
    public function update($id, $attributes)
    {
        return $this->client->collection(env('MONGODB_PRODUCTS_COLLECTION'))->update($id, $attributes);
    }

    /**
     * @param $attributes
     * @return Interfaces\DocumentInterface|mixed
     */
    public function create($attributes)
    {
        return $this->client->collection(env('MONGODB_PRODUCTS_COLLECTION'))->create($attributes);
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function delete($id)
    {
        return $this->client->collection(env('MONGODB_PRODUCTS_COLLECTION'))->delete($id);
    }
}
