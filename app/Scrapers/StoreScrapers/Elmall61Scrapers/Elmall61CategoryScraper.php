<?php

namespace App\Scrapers\StoreScrapers\Elmall61Scrapers;

use App\Crawler\Clients\SimpleClient;
use App\Crawler\Crawler;
use App\Crawler\Interfaces\ClientInterface;
use App\Scrapers\ScraperInterface;

class Elmall61CategoryScraper extends Crawler implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'elmall61.ru';

    /**
     * @param string $url
     * @return mixed|void
     */
    public function handle(string $url)
    {
        $content = $this->client->request('GET', $url, $this->requestOptions)->getContent();

        preg_match_all('/href=[\'"].+[&|&amp;]page=([0-9]+)[\'"]/siU', $content, $pages);

        parent::handle($url);

        if (!empty($pages) && !empty($pages[1])) {
            $lastPage = (int)end($pages[1]);

            for ($page = 1; $page <= $lastPage; $page++) {
                parent::handle($url . '&page=' . $page);
            }
        }
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

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, 'trade_list?group') !== false;
    }
}