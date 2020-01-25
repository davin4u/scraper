<?php

namespace App;

/**
 * Class ProductStatus
 * @package App
 */
class ProductStatus
{
    /**
     * Product is not available in the market
     * @var int
     */
    public static $OUT_OF_STOCK = 0;

    /**
     * Product can be pre-ordered
     * @var int
     */
    public static $PRE_ORDER = 1;

    /**
     * Product is almost gone
     * @var int
     */
    public static $LOW = 2;

    /**
     * Product in stock
     * @var int
     */
    public static $IN_STOCK = 3;
}
