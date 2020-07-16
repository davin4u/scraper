<?php

namespace App\Crawler\Extractors;

use App\Attribute;
use App\Crawler\Exceptions\CrawlerValidationException;
use App\Crawler\Extractor;
use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleAttributeMatcher;
use App\Crawler\Matchers\SimpleBrandMatcher;
use App\Crawler\Matchers\SimpleCategoryMatcher;

/**
 * Class ProductExtractor
 * @package App\Crawler\Extractors
 */
abstract class ProductExtractor extends Extractor
{
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
     * ProductExtractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        parent::__construct($content);

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
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getBrandName(): string;

    /**
     * @return string
     */
    abstract public function getCategoryName(): string;

    /**
     * @return array
     */
    abstract public function getPhotos(): array;

    /**
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * @return array
     */
    abstract public function getAttributes(): array;

    /**
     * @return float
     */
    abstract public function getPrice(): float;

    /**
     * @return string
     */
    abstract public function getCurrency(): string;

    /**
     * @return array
     * @throws CrawlerValidationException
     */
    public function handle()
    {
        $categoryId = (int)$this->matchCategory($this->clear($this->getCategoryName()));

        return $this->validate([
            'name' => $this->clear($this->getName()),
            'brand_id' => $this->matchBrand($this->clear($this->getBrandName())),
            'category_id' => $categoryId,
            'description' => $this->clear($this->getDescription()),
            'photos' => $this->getPhotos(),
            //'price' => $this->getPrice(),
            //'currency' => $this->clear($this->getCurrency()),
            'attributes' => $this->matchAttributes($this->getAttributes(), $categoryId)
        ]);
    }

    /**
     * @param array $data
     * @return array
     * @throws CrawlerValidationException
     */
    protected function validate(array $data): array
    {
        $data = array_filter($data, function ($item) {
            return !empty($item) && $item;
        });

        if (empty($data['name'])) {
            throw new CrawlerValidationException("name field is required");
        }

        if (empty($data['category_id']) || !$data['category_id']) {
            throw new CrawlerValidationException("category_id is required");
        }

        if (!empty($data['photos'])) {
            foreach ($data['photos'] as $url) {
                preg_match('/^https?:\/\/.+\.[jpe?g|png]*$/', $url, $match);

                if (empty($match)) {
                    throw new CrawlerValidationException("Photo path {$url} is not valid.");
                }
            }
        }

        return $data;
    }
}