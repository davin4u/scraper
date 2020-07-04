<?php

namespace App\Scrapers\StoreScrapers\DnsShopScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;
use App\Scrapers\Webdriver;

class DnsShopProductPageScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @var int
     */
    protected $delay = 10;

    /*
    public function handle(string $url)
    {
        $driver = webdriver()->init();

        $content = $driver->open($url)
            ->wait(3)
            ->getPageSource();

        $driver->quit();

        $this->saveDocument($url, $content);

        sleep(rand($this->delay - 3, $this->delay + 5));
    }
    */

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/product/') !== false;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return new SimpleClient(); // @TODO another client class should be developed to support webdriver scraping
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return static::$domain;
    }
}
