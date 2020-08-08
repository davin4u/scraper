<?php

namespace App\Scrapers\StoreScrapers\Elmall61Scrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;

class Elmall61ProductPageScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'elmall61.ru';

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

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, 'description?goods') !== false;
    }
}