<?php

namespace App\Parsers\Helpers;

/**
 * Interface BrandMatcher
 * @package App\Parsers\Helpers
 */
interface BrandMatcher
{
    /**
     * @param string $name
     * @return int
     */
    public function match(string $name) : int;
}
