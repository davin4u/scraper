<?php

namespace App\Parsers;

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
}
