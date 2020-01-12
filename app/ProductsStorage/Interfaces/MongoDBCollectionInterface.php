<?php

namespace App\ProductsStorage\Interfaces;

/**
 * Interface MongoDBCollectionInterface
 * @package App\ProductsStorage\Interfaces
 */
interface MongoDBCollectionInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes) : DocumentInterface;

    /**
     * @param $id
     * @return DocumentInterface
     */
    public function find($id) : DocumentInterface;

    /**
     * @param $id
     * @param array $attributes
     * @return DocumentInterface
     */
    public function update($id, array $attributes) : DocumentInterface;

    /**
     * @param $id
     * @return bool
     */
    public function delete($id) : bool;
}
