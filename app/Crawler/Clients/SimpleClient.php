<?php

namespace App\Crawler\Clients;

use App\Crawler\Exceptions\CrawlerHttpException;
use App\Crawler\HttpResponse;
use App\Crawler\Interfaces\ClientInterface;
use App\Crawler\Interfaces\ClientResponseInterface;
use GuzzleHttp\Client;

/**
 * Class SimpleClient
 * @package App\Crawler\Clients
 */
class SimpleClient implements ClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * SimpleClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $method
     * @param string $url
     * @return ClientResponseInterface
     * @throws CrawlerHttpException
     */
    public function request(string $method, string $url): ClientResponseInterface
    {
        try {
            return new HttpResponse($this->client->request($method, $url));
        }
        catch (\Exception $e) {
            throw new CrawlerHttpException($e->getMessage());
        }
    }
}