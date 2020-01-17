<?php

namespace App\Parsers\Helpers;

/**
 * Class SimpleCategoryMatcher
 * @package App\Parsers\Helpers
 */
class SimpleCategoryMatcher extends SimpleMatcher implements CategoryMatcher
{
    /**
     * @var string
     */
    protected static $model = \App\Category::class;
}
