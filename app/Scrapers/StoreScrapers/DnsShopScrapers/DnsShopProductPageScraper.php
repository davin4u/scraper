<?php

namespace App\Scrapers\StoreScrapers\DnsShopScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use App\Scrapers\Webdriver;

class DnsShopProductPageScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @var int
     */
    protected $delay = 10;

    /**
     * @param string $url
     * @return mixed|void
     * @throws \App\Exceptions\ScrapingTerminatedException
     */
    public function handle(string $url)
    {
        /** @var Webdriver $driver */
        $driver = webdriver()->init();

        $content = $driver->open($url)
            ->wait(3)
            ->getPageSource();

        $driver->quit();

        $this->saveDocument($url, $content);

        sleep(rand($this->delay - 3, $this->delay + 5));
    }


    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/product/') !== false;
    }
}
