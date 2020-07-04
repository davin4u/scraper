<?php

namespace App\Scrapers\StoreScrapers\BroadcaststoreeuropeScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;

class BroadcaststoreeuropeProductPageScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'broadcaststoreeurope.com';

    /*
    public function handle(string $url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $content = $response->getBody()->getContents();

        $this->saveDocument($url, $content);

        sleep(rand($this->delay - 2, $this->delay + 4));
    }*/

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        $parts = array_filter(explode('/', parse_url($url, PHP_URL_PATH)), function ($item) { return strlen(trim($item)) > 0; });

        return strpos($url, static::$domain) !== false && count($parts) === 3;
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