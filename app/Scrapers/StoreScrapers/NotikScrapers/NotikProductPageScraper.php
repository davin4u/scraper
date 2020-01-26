<?php

namespace App\Scrapers\StoreScrapers\NotikScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

/**
 * Class NotikProductPageScraper
 * @package App\Scrapers\StoreScrapers\NotikScrapers
 */
class NotikProductPageScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'notik.ru';

    /**
     * @var int
     */
    protected $delay = 10;

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

        sleep(rand($this->delay - 3, $this->delay + 2));
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/goods/') !== false;
    }
}
