<?php

namespace App\Matcher\ProductMatcherPlugins;

use App\Matcher\MatcherPluginInterface;
use App\Product;

/**
 * Class MatchByManufacturerId
 * @package App\Matcher\ProductMatcherPlugins
 */
class MatchByManufacturerId implements MatcherPluginInterface
{
    /**
     * @return MatcherPluginInterface
     */
    public static function getInstance(): MatcherPluginInterface
    {
        return new static();
    }

    /**
     * @param Product $product
     * @return array
     */
    public function match(Product $product) : array
    {
        if (!$product->manufacturer_id) {
            return [];
        }

        $matches = Product::query()->where('manufacturer_id', $product->manufacturer_id)
                                   ->whereNotIn('id', [$product->id])
                                   ->get()
                                   ->pluck('id')
                                   ->toArray();

        return $matches;
    }
}
