<?php

namespace App\Scrapers;

use App\ClassesFactory;
use App\Exceptions\ScraperNotFoundException;

/**
 * Class ScraperFactory
 * @package App\Scrapers
 */
class ScraperFactory extends ClassesFactory
{
    /**
     * @var string
     */
    protected static $directory = __DIR__ . DIRECTORY_SEPARATOR . 'StoreScrapers';

    /**
     * @param string $url
     * @return mixed
     * @throws ScraperNotFoundException
     */
    public function get(string $url)
    {
        foreach (static::$classes as $scraper) {
            /** @var BaseScraper $scraper */

            if (class_exists($scraper) && $scraper::canHandle($url)) {
                return new $scraper;
            }
        }

        throw new ScraperNotFoundException("There is no scraper for given url.");
    }
}
