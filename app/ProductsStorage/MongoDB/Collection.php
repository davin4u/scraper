<?php

namespace App\ProductsStorage\MongoDB;

use App\Exceptions\ProductInsertException;
use App\Exceptions\ProductNotFoundException;
use App\ProductsStorage\Interfaces\DocumentInterface;
use App\ProductsStorage\Interfaces\MongoDBCollectionInterface;

/**
 * Class Collection
 * @package App\ProductsStorage\MongoDB
 */
class Collection implements MongoDBCollectionInterface
{
    /**
     * @var \MongoDB\Collection
     */
    protected $collection;

    /**
     * Collection constructor.
     * @param \MongoDB\Collection $collection
     */
    public function __construct(\MongoDB\Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param array $attributes
     * @return Document|mixed
     * @throws ProductInsertException
     * @throws ProductNotFoundException
     */
    public function create(array $attributes) : DocumentInterface
    {
        $result = $this->collection->insertOne($attributes);

        if ($result instanceof \MongoDB\InsertOneResult) {
            /** @var \MongoDB\BSON\ObjectId $id */
            $id = $result->getInsertedId();

            /** @var \MongoDB\Model\BSONDocument $doc */
            $doc = $this->collection->findOne(['_id' => $id]);

            if (!is_null($doc)) {
                return new Document($doc);
            }

            throw new ProductNotFoundException("Product $id was not found.");
        }

        throw new ProductInsertException("Something went wrong.");
    }

    /**
     * @param $id
     * @return DocumentInterface
     * @throws ProductNotFoundException
     */
    public function find($id) : DocumentInterface
    {
        /** @var \MongoDB\Model\BSONDocument $doc */
        $doc = $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

        if (!is_null($doc)) {
            return new Document($doc);
        }

        throw new ProductNotFoundException("Product $id was not found.");
    }

    /**
     * @param array $filter
     * @param array $options
     * @return array
     */
    public function where($filter = [], array $options = []) : array
    {
        $docs = $this->collection->find($filter, $options)->toArray();

        if (!empty($docs)) {
            $docs = array_map(function ($doc) {
                return new Document($doc);
            }, $docs);
        }

        return $docs;
    }

    /**
     * @param $id
     * @param array $attributes
     * @return DocumentInterface
     * @throws ProductNotFoundException
     */
    public function update($id, array $attributes): DocumentInterface
    {
        /** @var \MongoDB\UpdateResult $result */
        $result = $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => $attributes]
        );

        if ($result->getMatchedCount() <= 0) {
            throw new ProductNotFoundException("No product found for given criteria.");
        }

        return $this->find($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        /** @var \MongoDB\DeleteResult $result */
        $result = $this->collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

        return $result->getDeletedCount() > 0;
    }
}
