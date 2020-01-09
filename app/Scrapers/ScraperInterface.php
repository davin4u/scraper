<?php

namespace App\Scrapers;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function handle(string $url);

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url) : bool;
}
