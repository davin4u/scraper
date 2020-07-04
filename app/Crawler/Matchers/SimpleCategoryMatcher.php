<?php

namespace App\Crawler\Matchers;

use App\Crawler\Interfaces\Matchable;

/**
 * Class SimpleCategoryMatcher
 * @package App\Crawler\Matchers
 */
class SimpleCategoryMatcher extends SimpleMatcher implements Matchable
{
    /**
     * @var string
     */
    protected $model = \App\Category::class;
}
