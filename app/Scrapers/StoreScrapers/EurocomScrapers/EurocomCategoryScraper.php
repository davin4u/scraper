<?php

namespace App\Scrapers\StoreScrapers\EurocomScrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;

class EurocomCategoryScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.eurocom.fr';

    /*
    public function handle(string $url)
    {
        $client = new Client();

        $baseUrl = $this->getBaseUrl($url);
        $page = 1;

        while (true) {
            $url = $baseUrl . '?p=' . $page;

            $response = $client->request('GET', $url);

            $content = $response->getBody()->getContents();

            if (! $this->containProducts($content)) {
                return false;
            }

            $this->saveDocument($url, $content);

            if ($this->isLastPage($content)) {
                return false;
            }

            $page++;

            sleep(rand($this->delay - 3, $this->delay + 5));
        }
    }*/

    /**
     * @param $content
     * @return bool
     */
    private function isLastPage($content)
    {
        preg_match('/\<li id="pagination_next".+\<\/li\>/siU', $content, $matches);

        if (isset($matches[0])) {
            preg_match('/\<a.+\<\/a\>/siU', $matches[0], $link);

            return strpos($matches[0], 'disabled') !== false && !isset($link[0]);
        }

        return true;
    }

    /**
     * @param $content
     * @return bool
     */
    private function containProducts($content)
    {
        return strpos($content, 'id="product_list"') !== false && strpos($content, 'ajax_block_product') !== false;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        $parts = array_filter(explode('/', parse_url($url, PHP_URL_PATH)), function ($item) { return !empty($item); });

        return strpos($url, static::$domain) !== false && count($parts) === 1;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return new SimpleClient();
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return static::$domain;
    }
}