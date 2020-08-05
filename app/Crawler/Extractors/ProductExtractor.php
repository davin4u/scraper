<?php

namespace App\Crawler\Extractors;

use App\Attribute;
use App\Crawler\Exceptions\CrawlerValidationException;
use App\Crawler\Extractor;
use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleAttributeMatcher;
use App\Crawler\Matchers\SimpleBrandMatcher;
use App\Crawler\Matchers\SimpleCategoryMatcher;
use App\Repositories\ProductsRepository;
use App\Repositories\StoreProductsRepository;

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
     * @var ProductsRepository
     */
    private $products;

    /**
     * @var StoreProductsRepository
     */
    private $storeProducts;

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

        $this->products = new ProductsRepository();

        $this->storeProducts = new StoreProductsRepository();
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
            $attrName = $this->clear($attrName);
            $value = $this->clear($value);

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
    abstract public function getUrl(): string;

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
     * @return int
     */
    abstract public function getStoreId(): int;

    /**
     * @return float
     */
    abstract public function getPrice(): float;

    /**
     * @return float
     */
    abstract public function getOldPrice(): float;

    /**
     * @return string
     */
    abstract public function getCurrency(): string;

    /**
     * @return string
     */
    abstract public function getSku(): string;

    /**
     * @return bool
     */
    abstract public function getIsAvailable(): bool;

    /**
     * @return string
     */
    abstract public function getDeliveryText(): string;

    /**
     * @return string
     */
    abstract public function getDeliveryDays(): string;

    /**
     * @return float
     */
    abstract public function getDeliveryPrice(): float;

    /**
     * @return string
     */
    abstract public function getBenefits(): string;

    /**
     * @return string
     */
    abstract public function getMetaTitle(): string;

    /**
     * @return string
     */
    abstract public function getMetaDescription(): string;

    /**
     * @return string
     */
    abstract public function getMetaKeywords(): string;

    /**
     * @throws CrawlerValidationException
     * @throws \App\Exceptions\ProductNotFoundException
     */
    public function handle()
    {
        if ($this->withOption('init')) {
            $this->handleInitialParsing();
        }
        else {
            $this->handleRegularParsing();
        }
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

    /**
     * @throws CrawlerValidationException
     * @throws \App\Exceptions\ProductNotFoundException
     */
    private function handleInitialParsing()
    {
        $this->products->createOrUpdate(
            $this->validate(
                $this->getProductInitialData()
            )
        );
    }

    /**
     * @throws CrawlerValidationException
     * @throws \App\Exceptions\ProductNotFoundException
     */
    private function handleRegularParsing()
    {
        $this->storeProducts->createOrUpdate(
            $this->validate(
                $this->getProductRegularData()
            )
        );
    }

    /**
     * @return array
     */
    private function getProductInitialData()
    {
        $categoryId = (int)$this->matchCategory($this->clear($this->getCategoryName()));

        return [
            'name' => $this->clear($this->getName()),
            'brand_id' => $this->matchBrand($this->clear($this->getBrandName())),
            'category_id' => $categoryId,
            'description' => $this->clear($this->getDescription()),
            'photos' => $this->getPhotos(),
            'attributes' => $this->matchAttributes($this->getAttributes(), $categoryId)
        ];
    }

    /**
     * @return array
     */
    private function getProductRegularData()
    {
        $categoryId = (int)$this->matchCategory($this->clear($this->getCategoryName()));

        return [
            'store_id' => $this->getStoreId(),
            'url' => $this->getUrl(),
            'sku' => $this->clear($this->getSku()),
            'name' => $this->clear($this->getName()),
            'brand_id' => $this->matchBrand($this->clear($this->getBrandName())),
            'category_id' => $categoryId,
            'description' => $this->clear($this->getDescription()),
            'price' => $this->getPrice(),
            'old_price' => $this->getOldPrice(),
            'currency' => $this->clear($this->getCurrency()),
            'is_available' => $this->getIsAvailable(),
            'delivery_text' => $this->clear($this->getDeliveryText()),
            'delivery_days' => $this->clear($this->getDeliveryDays()),
            'delivery_price' => $this->getDeliveryPrice(),
            'benefits' => $this->clear($this->getBenefits()),
            'meta_title' => $this->clear($this->getMetaTitle()),
            'meta_description' => $this->clear($this->getMetaDescription()),
            'meta_keywords' => $this->clear($this->getMetaKeywords())
        ];
    }
}