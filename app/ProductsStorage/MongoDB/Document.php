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
        return $this->prepareAttributes((array) $this->doc);
    }

    /**
     * @return \MongoDB\Model\BSONDocument
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @param $attributes
     * @return mixed
     */
    private function prepareAttributes($attributes)
    {
        foreach ($attributes as $key => $attr) {
            if ($attr instanceof \MongoDB\Model\BSONArray) {
                $attributes[$key] = $this->prepareAttributes((array) $attr);
            }
        }

        return $attributes;
    }
}
