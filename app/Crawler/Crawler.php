<?php

namespace App\Crawler;

use App\Crawler\Interfaces\ClientInterface;
use App\Crawler\Interfaces\CrawlerPluginInterface;
use App\Crawler\Plugins\RemoveCss;
use App\Crawler\Plugins\RemoveJavascript;
use App\Crawler\Plugins\RemoveSpaces;
use Carbon\Carbon;

/**
 * Class Crawler
 * @package App\Crawler
 */
abstract class Crawler
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var int
     */
    protected $delay = 10;

    /**
     * @var array
     */
    protected $requestOptions = [];

    /**
     * @var DocumentsRepository
     */
    protected $documents;

    /**
     * @var array
     */
    protected $clearContentPlugins = [
        RemoveJavascript::class,
        RemoveCss::class,
        RemoveSpaces::class
    ];

    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        $this->client = $this->getHttpClient();

        $this->documents = new DocumentsRepository();
    }

    /**
     * @param string $url
     */
    public function handle(string $url)
    {
        $this->saveDocument($url, $this->client->request('GET', $url, $this->requestOptions)->getContent());

        sleep($this->getDelay());
    }

    /**
     * @return ClientInterface
     */
    abstract public function getHttpClient(): ClientInterface;

    /**
     * @return string
     */
    abstract public function getDomainName(): string;

    /**
     * @param string $url
     * @param string $content
     */
    public function saveDocument(string $url, string $content)
    {
        $this->documents
            ->domain($this->getDomainName())
            ->date(Carbon::now())
            ->fileName($this->convertUrlToHashName($url))
            ->put($this->clearContent($content));
    }

    /**
     * @param string $url
     * @return string
     */
    protected function convertUrlToHashName(string $url): string
    {
        return md5($url) . '.html';
    }

    /**
     * @param string $content
     * @return string
     */
    protected function clearContent(string $content): string
    {
        if (!empty($this->clearContentPlugins)) {
            foreach ($this->clearContentPlugins as $pluginClass) {
                /** @var CrawlerPluginInterface $plugin */
                $plugin = new $pluginClass();

                $content = $plugin->handle($content);
            }
        }

        return $content;
    }

    /**
     * @return int
     */
    private function getDelay(): int
    {
        $delay = $this->delay >= 8 ? $this->delay : 8;

        return rand($delay - 3, $delay + 5);
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getBaseUrl(string $url): string
    {
        return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
    }
}