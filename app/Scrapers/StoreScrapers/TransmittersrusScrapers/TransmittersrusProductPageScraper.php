<?php

namespace App\Scrapers\StoreScrapers\TransmittersrusScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

class TransmittersrusProductPageScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.transmittersrus.com';

    /**
     * @param string $url
     * @return mixed
     */
    public function handle(string $url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $content = $response->getBody()->getContents();

        $this->saveDocument($url, $content);
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/shop/') !== false;
    }
}