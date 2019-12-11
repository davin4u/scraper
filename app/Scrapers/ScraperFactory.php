<?php

namespace App\Scrapers;

use App\Exceptions\ScraperNotFoundException;

/**
 * Class ScraperFactory
 * @package App\Scrapers
 */
class ScraperFactory
{
    /**
     * @var string
     */
    protected static $scrapersDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'StoreScrapers';

    /**
     * @var array
     */
    protected static $scrapers = [];

    /**
     * @param string $url
     * @return mixed
     * @throws ScraperNotFoundException
     */
    public static function get(string $url)
    {
        if (empty(static::$scrapers)) {
            static::loadScrapers();
        }

        foreach (static::$scrapers as $scraper) {
            $scraperClass = 'App\Scrapers\StoreScrapers\\' . $scraper;

            if (class_exists($scraperClass) && $scraperClass::isRelatedToUrl($url)) {
                return new $scraperClass;
            }
        }

        throw new ScraperNotFoundException("There is no scraper for given url.");
    }

    private static function loadScrapers()
    {
        $stores = scandir(static::$scrapersDirectory);

        foreach ($stores as $storeScrapersDirectory) {
            if ($storeScrapersDirectory !== '.' && $storeScrapersDirectory !== '..') {
                $scrapers = scandir(static::$scrapersDirectory . DIRECTORY_SEPARATOR . $storeScrapersDirectory);

                foreach ($scrapers as $scraper) {
                    if ($scraper !== '.' && $scraper !== '..') {
                        static::$scrapers[] = $storeScrapersDirectory . '\\' . str_replace('.php', '', $scraper);
                    }
                }
            }
        }
    }
}
