<?php

namespace App\Scrapers\StoreScrapers\TransmittersrusScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

class TransmittersrusProductPageScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.transmittersrus.com';

    /*
    public function handle(string $url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $content = $response->getBody()->getContents();

        $this->saveDocument($url, $content);
    }*/

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/shop/') !== false;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return new SimpleClient();
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return static::$domain;
    }
}