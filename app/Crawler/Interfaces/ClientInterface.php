<?php

namespace App\Crawler\Interfaces;

/**
 * Interface ClientInterface
 * @package App\Crawler\Interfaces
 */
interface ClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ClientResponseInterface
     */
    public function request(string $method, string $url, array $options = []): ClientResponseInterface;
}