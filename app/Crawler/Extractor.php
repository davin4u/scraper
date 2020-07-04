<?php

namespace App\Crawler;

use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleBrandMatcher;
use App\Crawler\Matchers\SimpleCategoryMatcher;

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
     * @var Matchable
     */
    private $brandMatcher;

    /**
     * @var Matchable
     */
    private $categoryMatcher;

    /**
     * Extractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = new \Symfony\Component\DomCrawler\Crawler($content);

        $this->brandMatcher = $this->getBrandMatcher();

        $this->categoryMatcher = $this->getCategoryMatcher();
    }

    /**
     * @return Matchable
     */
    protected function getBrandMatcher(): Matchable
    {
        return new SimpleBrandMatcher();
    }

    /**
     * @return Matchable
     */
    protected function getCategoryMatcher(): Matchable
    {
        return new SimpleCategoryMatcher();
    }

    /**
     * @param string $brand
     * @return int
     */
    public function matchBrand(string $brand): int
    {
        return $this->brandMatcher->match($brand);
    }

    /**
     * @param string $category
     * @return int
     */
    public function matchCategory(string $category): int
    {
        return $this->categoryMatcher->match($category);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function clear(string $value): string
    {
        return trim(strip_tags($value));
    }
}