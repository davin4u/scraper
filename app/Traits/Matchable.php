<?php

namespace App\Traits;

trait Matchable
{
    /**
     * @param string $sep
     * @return string
     */
    public function mapAsString($sep = '|')
    {
        if (!$this->map) {
            return '';
        }

        return implode($sep, $this->map);
    }
}
