<?php

namespace App\Scrapers\StoreScrapers\DnsShopScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use App\Scrapers\Webdriver;

/**
 * Class DnsShopCategoryScraper
 * @package App\Scrapers\StoreScrapers\DnsShopScrapers
 */
class DnsShopCategoryScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @var int
     */
    protected $delay = 15;

    /**
     * @param string $url
     * @return mixed|void
     * @throws \App\Exceptions\ScrapingTerminatedException
     */
    public function handle(string $url)
    {
        $preparedUrl = $this->getBaseUrl($url);
        $page = 1;

        while (true) {
            /** @var Webdriver $driver */
            $driver = webdriver()->init();

            $url = $preparedUrl . '?p=' . $page;

            //@TODO check if we have to execute webdriver's close() method after each iteration
            $content = $driver->open($url)
                //->wait(rand(3, 5))
                //->screenshot(storage_path('app/scraper/screenshots'))
                ->getPageSource();

            $driver->quit();

            if (! $this->containProducts($content)) {
                break;
            }

            $this->saveDocument($url, $content);

            $page++;

            sleep(rand($this->delay - 3, $this->delay + 5));
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/product/') === false;
    }

    /**
     * @param string $content
     * @return bool
     */
    private function containProducts($content)
    {
        if (is_null($content)) {
            return false;
        }

        return strpos($content, 'n-catalog-product__main') !== false;
    }
}
