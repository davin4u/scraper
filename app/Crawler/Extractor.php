<?php

namespace App\Crawler;

/**
 * Class Extractor
 * @package App\Crawler
 */
class Extractor
{
    /**
     * @var \Symfony\Component\DomCrawler\Crawler $content
     */
    protected $content;

    /**
     * Extractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = new \Symfony\Component\DomCrawler\Crawler($content);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function clear(string $value): string
    {
        if (is_null($value)) {
            return $value;
        }

        return trim(strip_tags($value));
    }
}