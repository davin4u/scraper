<?php

namespace App\Matcher\ProductMatcherPlugins;

use App\Matcher\MatcherPluginInterface;
use App\Product;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;
use App\ProductsStorage\MongoDB\Document;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Support\Arr;

/**
 * Class MatchByModel
 * @package App\Matcher\ProductMatcherPlugins
 */
class MatchByModel implements MatcherPluginInterface
{
    /**
     * @var ProductsStorageInterface|null
     */
    protected static $storage = null;

    /** @var ProductAttributesRepository|null */
    protected static $attributes = null;

    /**
     * MatchByModel constructor.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        if (is_null(static::$storage)) {
            static::$storage = app()->make(ProductsStorageInterface::class);
        }

        if (is_null(static::$attributes)) {
            static::$attributes = app()->make(ProductAttributesRepository::class);
        }
    }

    /**
     * @return MatcherPluginInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getInstance(): MatcherPluginInterface
    {
        return new static();
    }

    /**
     * @param Product $product
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function match(Product $product): array
    {
        if ($product->category_id) {
            $attributes = Arr::get($product->getStorableDocument(), 'attributes', []);

            $modelAttribute = static::$attributes->recognizeAttribute('Модель', $product->category_id);

            if ($modelAttribute && !empty($attributes[$modelAttribute->attribute_key])) {
                $matches = static::$storage->where(
                    [
                        'attributes.' . $modelAttribute->attribute_key => [
                            '$regex'   => $attributes[$modelAttribute->attribute_key],
                            '$options' => 'i'
                        ]
                    ],
                    ['limit' => 10]
                );

                if (count($matches) > 0) {
                    $matches = array_map(function ($match) {
                        /** @var Document $match */

                        return $match->getDocumentId();
                    }, array_filter($matches, function ($match) use ($product) {
                        /** @var Document $match */

                        return $match->getDocumentId() !== $product->getStorableDocumentId();
                    }));

                    $matches = Product::whereStorable($matches)->whereNotIn('id', [$product->id])
                                                               ->whereNotIn('domain_id', [$product->domain_id])
                                                               ->get()
                                                               ->pluck('id')
                                                               ->toArray();

                    return $matches;
                }
            }
        }

        return [];
    }
}
