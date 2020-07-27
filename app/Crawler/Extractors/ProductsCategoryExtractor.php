<?php

namespace App\Crawler\Extractors;

use App\Crawler\Exceptions\CrawlerValidationException;
use App\Crawler\Extractor;
use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleBrandMatcher;
use App\Crawler\Matchers\SimpleCategoryMatcher;
use App\Repositories\ProductsRepository;
use App\ScraperJob;

/**
 * Class ProductsCategoryExtractor
 * @package App\Crawler\Extractors
 */
abstract class ProductsCategoryExtractor extends Extractor
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
     * @var ProductsRepository
     */
    private $products;

    /**
     * ProductExtractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        parent::__construct($content);

        $this->brandMatcher = $this->getBrandMatcher();

        $this->categoryMatcher = $this->getCategoryMatcher();

        $this->products = new ProductsRepository();
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
     * @return array
     */
    abstract public function getProducts(): array;

    /**
     * @throws CrawlerValidationException
     * @throws \App\Exceptions\ProductNotFoundException
     */
    public function handle()
    {
        $products = $this->getProducts();

        foreach ($products as &$product) {
            if (!empty($product['category'])) {
                $product['category_id'] = $this->matchCategory($this->clear($product['category']));
                unset($product['category']);
            }

            if (!empty($product['brand'])) {
                $product['brand_id'] = $this->matchBrand($this->clear($product['brand']));
                unset($product['brand']);
            }
        }

        $products = $this->validate($products);

        if ($this->withOption('collect-links')) {
            $this->storeScrapingJobs($products);

            return;
        }

        $this->products->bulkCreateOrUpdate($this->validate($products));
    }

    /**
     * @param array $products
     * @return array
     * @throws CrawlerValidationException
     */
    protected function validate(array $products): array
    {
        foreach ($products as $product) {
            if (empty($product['url'])) {
                throw new CrawlerValidationException("Product url is required.");
            }

            if (empty($product['name'])) {
                throw new CrawlerValidationException("Product name is required.");
            }

            if (empty($product['category_id'])) {
                throw new CrawlerValidationException("Product category was not recognized.");
            }

            if (empty($product['brand_id'])) {
                throw new CrawlerValidationException("Product brand was not recognized.");
            }
        }

        return $products;
    }

    /**
     * @param array $products
     */
    protected function storeScrapingJobs(array $products)
    {
        foreach ($products as $product) {
            ScraperJob::create([
                'url' => $product['url'],
                'user_id' => 1
            ]);
        }
    }
}