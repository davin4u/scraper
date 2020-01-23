<?php

namespace App\Traits;

use App\ProductsStorage\Interfaces\DocumentInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Support\Arr;

trait Storable
{
    /**
     * @var array
     */
    protected $storable = [];

    /** @var ProductsStorageInterface $storage */
    protected static $storage;

    /**
     * @return array|mixed|\MongoDB\Model\BSONDocument
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStorableDocument()
    {
        if (!empty($this->storable)) {
            return $this->storable;
        }

        $storableKey = property_exists($this, 'storableKey') ? $this->storableKey : 'storable_id';

        if (is_null($this->{$storableKey})) {
            return [];
        }

        if (is_null(static::$storage)) {
            static::$storage = app()->make(ProductsStorageInterface::class);
        }

        /** @var DocumentInterface $doc */
        $doc = static::$storage->find($this->{$storableKey});

        if (!is_null($doc)) {
            $this->storable = $doc->getDoc(true);
        }

        return $this->storable;
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStorableAttributes()
    {
        if (is_null($this->category_id)) {
            return [];
        }

        if (empty($this->storable)) {
            $this->getStorableDocument();
        }

        /** @var ProductAttributesRepository $attributes */
        $attributes = app()->make(ProductAttributesRepository::class);

        return $attributes->getCategoryAttributes($this->category_id, Arr::get($this->storable, 'attributes', []));
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStorableImages()
    {
        if (empty($this->storable)) {
            $this->getStorableDocument();
        }

        return Arr::get($this->storable, 'images', []);
    }

    /**
     * @param array $data
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function saveStorableDocument(array $data)
    {
        if (is_null(static::$storage)) {
            static::$storage = app()->make(ProductsStorageInterface::class);
        }

        $attributes = Arr::get($data, 'attributes', []);
        $images     = Arr::get($data, 'images', []);

        $storableObject = array_merge($this->toStorableDocument(), ['attributes' => $attributes], ['images' => $images]);

        $storableKey = property_exists($this, 'storableKey') ? $this->storableKey : 'storable_id';

        if (is_null($this->{$storableKey})) {
            /** @var DocumentInterface $doc */
            $doc = static::$storage->create($storableObject);

            $this->update([
                $storableKey => $doc->getDocumentId()
            ]);
        }
        else {
            static::$storage->update($this->{$storableKey}, $storableObject);
        }
    }
}
