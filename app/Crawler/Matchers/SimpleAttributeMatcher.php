<?php

namespace App\Crawler\Matchers;

use App\Crawler\Interfaces\Matchable;

/**
 * Class SimpleAttributeMatcher
 * @package App\Crawler\Matchers
 */
class SimpleAttributeMatcher extends SimpleMatcher implements Matchable
{
    /**
     * @var string
     */
    protected $model = \App\Attribute::class;
}