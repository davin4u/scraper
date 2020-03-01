<?php

namespace App\Scrapers\StoreScrapers\BroadcaststoreeuropeScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

class BroadcaststoreeuropeCategoryScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'broadcaststoreeurope.com';

    /**
     * @param string $url
     * @return mixed
     */
    public function handle(string $url)
    {
        $client      = new Client();
        $page        = 1;
        $limit       = 48;

        $parts = array_filter(explode('/', parse_url($url, PHP_URL_PATH)), function ($item) { return strlen(trim($item)) > 0; });

        $id = end($parts);

        while (true) {
            $url = "https://broadcaststoreeurope.com/json/products?field=categoryId&filterGenerate=true&id={$id}&limit={$limit}&page={$page}";

            $response = $client->request(
                'GET',
                $url
            );

            $content = $response->getBody()->getContents();

            $json = json_decode($content, true);

            if (empty($json['products'])) {
                break;
            }

            $this->saveDocument($url, $content);

            $page++;

            sleep(rand($this->delay - 5, $this->delay + 2));
        }
    }

    /**
     * @param string $content
     * @return string
     */
    protected function clearDocument(string $content)
    {
        // we don't need to clear document here since this is json string
        return $content;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        $parts = array_filter(explode('/', parse_url($url, PHP_URL_PATH)), function ($item) { return strlen(trim($item)) > 0; });

        return strpos($url, static::$domain) !== false && count($parts) === 2;
    }
}