<?php

namespace App\Scrapers\StoreScrapers\TransmittersrusScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;

class TransmittersrusCategoryScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.transmittersrus.com';

    /**
     * @param string $url
     * @return mixed|void
     * @throws \App\Exceptions\ScrapingTerminatedException
     */
    public function handle(string $url)
    {
        $preparedUrl = $this->getBaseUrl($url);
        $page = 1;
        $client = new Client();

        while (true) {
            $url = $preparedUrl;

            if ($page > 1) {
                $url = $preparedUrl . "/page/{$page}/";
            }

            $response = $client->request('GET', $url);

            $content = $response->getBody()->getContents();

            if (! $this->containProducts($content)) {
                break;
            }

            $this->saveDocument($url, $content);

            $page++;

            sleep(rand($this->delay - 3, $this->delay + 5));
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, '/product-category/') !== false;
    }

    /**
     * @param string $content
     * @return bool
     */
    protected function containProducts(string $content)
    {
        return strpos($content, 'woocommerce-result-count') !== false;
    }
}