<?php

namespace App\Scrapers\StoreScrapers\EldoradoScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;
use App\Scrapers\Webdriver;

class EldoradoCategoryScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'eldorado.ru';

    /**
     * @var int
     */
    protected $delay = 15;


    /*
    public function handle(string $url)
    {
        $preparedUrl = $this->getBaseUrl($url);
        $page = 1;

        while (true) {
            $driver = webdriver()->init();

            $url = $preparedUrl . '?page=' . $page;

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
    }*/

    /**
     * @param $content
     * @return bool
     */
    private function containProducts($content)
    {
        if (is_null($content)) {
            return false;
        }

        return strpos($content, 'listing-container') !== false;
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
