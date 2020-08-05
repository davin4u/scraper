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
     * @var array
     */
    protected $options = [];

    /**
     * Extractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = new \Symfony\Component\DomCrawler\Crawler($content);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $option
     * @return bool
     */
    protected function withOption($option): bool
    {
        return in_array($option, $this->options);
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

        return trim(strip_tags(html_entity_decode($value)));
    }
}