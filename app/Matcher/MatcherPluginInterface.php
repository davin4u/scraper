<?php

namespace App\Matcher;

use App\Product;

/**
 * Interface MatcherPluginInterface
 * @package App\Matcher
 */
interface MatcherPluginInterface
{
    /**
     * @return MatcherPluginInterface
     */
    public static function getInstance() : MatcherPluginInterface;

    /**
     * @param Product $product
     * @return array
     */
    public function match(Product $product) : array;
}
