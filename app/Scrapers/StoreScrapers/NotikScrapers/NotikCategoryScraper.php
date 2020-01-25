<?php

namespace App\Scrapers\StoreScrapers\NotikScrapers;

use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NotikCategoryScraper
 * @package App\Scrapers\StoreScrapers\NotikScrapers
 */
class NotikCategoryScraper extends BaseScraper implements ScraperInterface
{
    /**
     * @var string
     */
    protected static $domain = 'notik.ru';

    /**
     * @var int
     */
    protected $delay = 15;

    /**
     * @param string $url
     * @return mixed
     */
    public function handle(string $url)
    {
        $preparedUrl = $this->getBaseUrl($url);
        $client      = new Client();
        $page        = 1;
        $lastPage    = null;

        do {
            $url = $preparedUrl . '?page=' . $page . '&sortby=price';

            $response = $client->request('GET', $url);

            $content = $response->getBody()->getContents();

            if (is_null($lastPage)) {
                $lastPage = $this->getLastPage($content);
            }

            $this->saveDocument($url, $content);

            sleep(rand($this->delay - 5, $this->delay + 2));

            $page++;
        }
        while (!is_null($lastPage) && $page < $lastPage);
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url): bool
    {
        return strpos($url, static::$domain) !== false && strpos($url, 'search_catalog/filter') !== false;
    }

    /**
     * @param $content
     * @return int
     */
    private function getLastPage($content)
    {
        try {
            $paginator = (new Crawler($content))->filter('.paginator');

            if ($paginator && $paginator->count()) {
                $paginator = $paginator->first();

                $a = $paginator->filter('a');

                if ($a && $a->count()) {
                    $a = $a->last();

                    if ($a) {
                        return (int)trim($a->html());
                    }
                }
            }
        }
        catch (\InvalidArgumentException $e) {
            return 1;
        }

        return 1;
    }
}
