<?php

namespace App\Crawler;

use App\Attribute;
use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleAttributeMatcher;
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
     * @var Matchable
     */
    private $attributeMatcher;

    /**
     * Extractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = new \Symfony\Component\DomCrawler\Crawler($content);

        $this->brandMatcher = $this->getBrandMatcher();

        $this->categoryMatcher = $this->getCategoryMatcher();

        $this->attributeMatcher = $this->getAttributeMatcher();
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
     * @return Matchable
     */
    protected function getAttributeMatcher(): Matchable
    {
        return new SimpleAttributeMatcher();
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
     * @param array $attributes
     * @param int $categoryId
     * @return array
     */
    public function matchAttributes(array $attributes, int $categoryId): array
    {
        $matches = [];

        foreach ($attributes as $attrName => $value) {
            if ($match = $this->attributeMatcher->match($attrName, ['category_id' => $categoryId], true)) {
                /** @var Attribute $match */

                $matches[$match->attribute_key] = $value;
            }
        }

        return $matches;
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