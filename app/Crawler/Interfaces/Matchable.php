<?php

namespace App\Crawler\Interfaces;

/**
 * Interface Matchable
 * @package App\Crawler\Interfaces
 */
interface Matchable
{
    /**
     * @param string $name
     * @return int
     */
    public function match(string $name): int;
}