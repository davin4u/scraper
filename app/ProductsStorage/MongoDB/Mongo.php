<?php

namespace App\ProductsStorage\MongoDB;

/**
 * Class Mongo
 * @package App\ProductsStorage\MongoDB
 */
class Mongo
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @return Client
     */
    public static function client()
    {
        if (is_null(static::$client)) {
            static::$client = new Client();
        }

        return static::$client;
    }
}
