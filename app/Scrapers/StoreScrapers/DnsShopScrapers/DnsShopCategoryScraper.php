<?php

namespace App\Scrapers\StoreScrapers\DnsShopScrapers;

use App\Scrapers\BaseScraper;

/**
 * Class DnsShopCategoryScraper
 * @package App\Scrapers\StoreScrapers\DnsShopScrapers
 */
class DnsShopCategoryScraper extends BaseScraper
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
     * @throws \Exception
     */
    public function handle(string $url)
    {
        $preparedUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        $page = 1;

        while (true) {
            $url = $preparedUrl . '?p=' . $page;

            $content = $this->webdriver->open($url)
                                       ->wait(rand(3, 5))
                                       ->screenshot(storage_path('app/scraper/screenshots'))
                                       ->getPageSource();

            if (! $this->containProducts($content)) {
                break;
            }

            $this->saveDocument($url, $content);

            $page++;

            sleep(rand($this->delay - 3, $this->delay + 5));
        }

        $this->webdriver->close();
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
