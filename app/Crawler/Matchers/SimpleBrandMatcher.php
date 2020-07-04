<?php

namespace App\Crawler\Matchers;

use App\Crawler\Interfaces\Matchable;

/**
 * Class SimpleBrandMatcher
 * @package App\Crawler\Matchers
 */
class SimpleBrandMatcher extends SimpleMatcher implements Matchable
{
    /**
     * @var string
     */
    protected $model = \App\Brand::class;
}
