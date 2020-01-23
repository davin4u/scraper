<?php

namespace App\ProductsStorage\Interfaces;

/**
 * Interface DocumentInterface
 * @package App\ProductsStorage\Interfaces
 */
interface DocumentInterface
{
    /**
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes) : bool;

    /**
     * @return array
     */
    public function getAttributes() : array;

    /**
     * @return mixed
     */
    public function getDocumentId() : string;

    /**
     * @param bool $asArray
     * @return array|mixed|\MongoDB\Model\BSONDocument
     */
    public function getDoc(bool $asArray = false);

    /**
     * @return array
     */
    public function getImages() : array;
}
