<?php

namespace App\ProductsStorage\MongoDB;

use App\ProductsStorage\Interfaces\DocumentInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;

class Document implements DocumentInterface
{
    /**
     * @var \MongoDB\Model\BSONDocument
     */
    protected $doc;

    /**
     * @var ProductsStorageInterface
     */
    protected $storage;

    /**
     * Document constructor.
     * @param \MongoDB\Model\BSONDocument $doc
     */
    public function __construct(\MongoDB\Model\BSONDocument $doc)
    {
        $this->doc = $doc;

        $this->storage = app(ProductsStorageInterface::class);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function update($attributes = []) : bool
    {
        /** @var DocumentInterface $updated */
        $updated = $this->storage->update($this->doc->_id, $attributes);

        if ($updated) {
            $this->doc = $updated->getDoc();

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $doc = $this->getDoc(true);

        return isset($doc['attributes']) ? $doc['attributes'] : [];
    }

    /**
     * @param bool $asArray
     * @return array|mixed|\MongoDB\Model\BSONDocument
     */
    public function getDoc($asArray = false)
    {
        return $asArray ? $this->toArray() : $this->doc;
    }

    /**
     * @return array
     */
    public function getImages() : array
    {
        $doc = $this->getDoc(true);

        return isset($doc['images']) ? $doc['images'] : [];
    }

    /**
     * @return string
     */
    public function getDocumentId() : string
    {
        /** @var \MongoDB\BSON\ObjectId $objectId */
        $objectId = $this->doc->_id;

        return (string) $objectId;
    }

    /**
     * @return array|mixed
     */
    private function toArray()
    {
        $handler = function (array $properties, callable $handler) {
            foreach ($properties as $key => $prop) {
                if (is_iterable($prop)) {
                    $properties[$key] = $handler((array)$prop, $handler);
                }
            }

            return $properties;
        };

        return $handler((array) $this->doc, $handler);
    }
}
