<?php

namespace App\Traits;

use App\ProductsStorage\Interfaces\DocumentInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;

trait Storable
{
    /**
     * @var array
     */
    protected $storable = [];

    /** @var ProductsStorageInterface $storage */
    protected static $storage;

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStorableAttributes()
    {
        if (!empty($this->storable)) {
            return $this->storable;
        }

        if (is_null($this->storable_id)) {
            return [];
        }

        if (is_null(static::$storage)) {
            static::$storage = app()->make(ProductsStorageInterface::class);
        }

        /** @var DocumentInterface $doc */
        $doc = static::$storage->find($this->storable_id);

        if (!is_null($doc)) {
            $this->storable = $doc->getAttributes();
        }

        return $this->storable;
    }

    /**
     * @param $attributes
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function saveStorableDocument($attributes = [])
    {
        if (is_null(static::$storage)) {
            static::$storage = app()->make(ProductsStorageInterface::class);
        }

        $data = array_merge($this->toStorableDocument(), ['attributes' => $attributes]);

        $storableKey = property_exists($this, 'storableKey') ? $this->{$storableKey} : 'storable_id';

        if (is_null($this->{$storableKey})) {
            /** @var DocumentInterface $doc */
            $doc = static::$storage->create($data);

            $this->update([
                $storableKey => $doc->getDocumentId()
            ]);
        }
        else {
            static::$storage->update($this->{$storableKey}, $data);
        }
    }
}
