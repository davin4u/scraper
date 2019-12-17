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
     * @var string
     */
    protected $doNotStrip = '<html><head><title><meta><body><div><p><span><ol><ul><li><a><label><table><tr><td><img><section><footer><h1><h2><h3><h4><h5>';

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
            $this->clearDocument($content)
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

    /**
     * @param string $content
     * @return string
     */
    protected function clearDocument(string $content)
    {
        $content = str_replace('<!DOCTYPE html>', '', $content);
        $content = $this->removeJavascript($content);
        $content = $this->removeSpaces($content);

        return trim(strip_tags($content, $this->doNotStrip));
    }

    /**
     * @param string $content
     * @return string|string[]|null
     */
    private function removeJavascript(string $content)
    {
        return preg_replace('/\<script(.+)\<\/script\>/siU', '', $content);
    }

    /**
     * @param string $content
     * @return string
     */
    private function removeSpaces(string $content)
    {
        $content = str_replace("\n", "", $content);
        $content = preg_replace('/\s\s+/', ' ', $content);

        return $content;
    }
}
