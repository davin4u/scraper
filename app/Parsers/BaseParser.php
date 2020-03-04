<?php

namespace App\Parsers;

use App\Domain;
use App\Repositories\ProductAttributesRepository;

/**
 * Class BaseParser
 * @package App\Parsers
 */
class BaseParser
{
    /**
     * @var ProductAttributesRepository
     */
    protected $attributes;

    public function __construct()
    {
        $this->attributes = new ProductAttributesRepository();
    }

    /**
     * @return Domain|null
     */
    public function getDomain()
    {
        return Domain::where('name', static::$domain)->first();
    }
}
