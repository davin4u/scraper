<?php

namespace App\Parsers\Helpers;

/**
 * Interface CategoryMatcher
 * @package App\Parsers\Helpers
 */
interface CategoryMatcher
{
    /**
     * @param string $name
     * @return int
     */
    public function match(string $name) : int;
}
