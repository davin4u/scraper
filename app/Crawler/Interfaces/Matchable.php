<?php

namespace App\Crawler\Interfaces;

use Illuminate\Database\Eloquent\Model;

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
     * @return int|Model
     */
    public function match(string $name, array $props = [], bool $returnModel = false);
}