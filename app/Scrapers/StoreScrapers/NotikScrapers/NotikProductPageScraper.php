<?php

namespace App\Scrapers\StoreScrapers\NotikScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;

/**
 * Class NotikProductPageScraper
 * @package App\Scrapers\StoreScrapers\NotikScrapers
 */
class NotikProductPageScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.notik.ru';

    /**
     * @TODO verification disabled just for testing purpose, that mustn't happen on production
     * @var array
     */
    protected $requestOptions = ['verify' => false];

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/goods/') !== false;
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
