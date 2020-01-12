<?php

namespace App\ProductsStorage;

use App\Exceptions\ProductNotFoundException;
use App\ProductsStorage\Interfaces\MongoDBClientInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;

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
     * @param mixed ...$arguments
     * @return mixed
     */
    public function where(...$arguments)
    {
        // TODO: Implement where() method.
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
