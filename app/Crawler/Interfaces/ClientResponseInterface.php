<?php

namespace App\Crawler\Interfaces;

/**
 * Interface ClientResponseInterface
 * @package App\Crawler\Interfaces
 */
interface ClientResponseInterface
{
    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return bool
     */
    public function isSuccess(): bool;
}