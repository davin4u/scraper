<?php

namespace App\Scrapers;

use App\Webdriver;
use Illuminate\Support\Facades\Storage;

/**
 * Class BaseScraper
 * @package App\Scrapers
 */
abstract class BaseScraper
{
    /**
     * @var string
     */
    protected static $domain = '';

    /**
     * @var Webdriver
     */
    protected $webdriver = null;

    /**
     * @var int
     */
    protected $delay = 5;

    /**
     * BaseScraper constructor.
     */
    public function __construct()
    {
        $this->webdriver = webdriver()->init();
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function canHandle(string $url) : bool
    {
        return strpos($url, static::$domain) !== false;
    }

    /**
     * @param string $url
     * @return mixed
     */
    abstract public function handle(string $url);

    /**
     * @param $url
     * @param string $content
     */
    public function saveDocument($url, $content = '')
    {
        Storage::disk('local')->put(
            $this->getSourcesDirectory() . DIRECTORY_SEPARATOR . $this->convertUrlToHashName($url),
            $content
        );
    }

    /**
     * @return string
     */
    protected function getSourcesDirectory()
    {
        return 'scraper' . DIRECTORY_SEPARATOR . static::$domain . DIRECTORY_SEPARATOR . date('Y.m.d');
    }

    /**
     * @param $url
     * @return string
     */
    protected function convertUrlToHashName($url)
    {
        return md5($url) . '.html';
    }
}
