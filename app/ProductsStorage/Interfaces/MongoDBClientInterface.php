<?php

namespace App\ProductsStorage\Interfaces;

/**
 * Interface MongoDBClientInterface
 * @package App\ProductsStorage\Interfaces
 */
interface MongoDBClientInterface
{
    /**
     * @param string $name
     * @return MongoDBCollectionInterface
     */
    public function collection(string $name) : MongoDBCollectionInterface;
}
