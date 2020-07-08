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
     * @param array $props
     * @param bool $returnModel
     * @return int
     */
    public function match(string $name, array $props = [], bool $returnModel = false): int;
}