<?php

namespace App\Parsers\Helpers;

/**
 * Class SimpleBrandMatcher
 * @package App\Parsers\Helpers
 */
class SimpleBrandMatcher extends SimpleMatcher implements BrandMatcher
{
    /**
     * @var string
     */
    protected $model = \App\Brand::class;
}
