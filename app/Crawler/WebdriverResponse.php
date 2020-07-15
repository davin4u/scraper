<?php

namespace App\Crawler;

use App\Crawler\Interfaces\ClientResponseInterface;

/**
 * Class WebdriverResponse
 * @package App\Crawler
 */
class WebdriverResponse implements ClientResponseInterface
{
    /**
     * @var string|null
     */
    protected $realResponse;

    /**
     * WebdriverResponse constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->realResponse = $response;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->realResponse;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return !is_null($this->realResponse) ? 200 : 403;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatusCode() === 200;
    }
}