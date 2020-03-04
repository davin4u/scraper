<?php

namespace App\Scrapers\StoreScrapers\ProfmbroadcastScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

/**
 * Class ProfmbroadcastCategoryScraper
 * @package App\Scrapers\StoreScrapers\ProfmbroadcastScrapers
 */
class ProfmbroadcastCategoryScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.profmbroadcast.com';

    /**
     * @param string $url
     * @return mixed
     */
    public function handle(string $url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $content = $response->getBody()->getContents();

        if (! $this->containProducts($content)) {
            return false;
        }

        $this->saveDocument($url, $content);

        sleep(rand($this->delay - 3, $this->delay + 5));
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/products/cat/') !== false;
    }

    /**
     * @param string $content
     * @return bool
     */
    protected function containProducts(string $content)
    {
        return strpos($content, 'tiles clearfix feat_products') !== false
            && strpos($content, 'tile twentyfive tile_product') !== false;
    }
}