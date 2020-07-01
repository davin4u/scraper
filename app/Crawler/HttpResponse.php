<?php

namespace App\Crawler;

use App\Crawler\Exceptions\CrawlerHttpException;
use App\Crawler\Interfaces\ClientResponseInterface;

/**
 * Class HttpResponse
 * @package App\Crawler
 */
class HttpResponse implements ClientResponseInterface
{
    /** @var \Psr\Http\Message\ResponseInterface $realResponse */
    protected $realResponse;

    /**
     * HttpResponse constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->realResponse = $response;
    }

    /**
     * @return string
     * @throws CrawlerHttpException
     */
    public function getContent(): string
    {
        if (!$this->isSuccess()) {
            throw new CrawlerHttpException("Response content is not available.");
        }

        return $this->realResponse->getBody()->getContents();
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->realResponse->getStatusCode();
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return in_array($this->getStatusCode(), [200, 201, 202]);
    }
}