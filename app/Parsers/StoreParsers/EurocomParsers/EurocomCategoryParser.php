<?php

namespace App\Parsers\StoreParsers\EurocomParsers;

use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use App\ProductStatus;
use Symfony\Component\DomCrawler\Crawler;

class EurocomCategoryParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.eurocom.fr';

    /**
     * @param string $content
     * @return array|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(string $content)
    {
        $results = [];

        $page = new Crawler($content);

        $domain = $this->getDomain();

        $categoryName = null;
        $categoryId = null;

        try {
            $category = $page->filter('h1')->first();

            if ($category) {
                $categoryName = strip_tags($category->html());
            }
        }
        catch (\InvalidArgumentException $e) {};

        if (!is_null($categoryName) && $categoryName) {
            $categoryId = app()->make(CategoryMatcher::class)->match($categoryName);
        }

        $page->filter('#product_list > li')->each(function (Crawler $item) use (&$results, $domain, $categoryId) {
            $product = [];

            $product['domain_id'] = $domain->id;
            $product['category_id'] = $categoryId;

            // Name, Url
            try {
                $h3 = $item->filter('h3')->first();

                if ($h3) {
                    $product['name'] = trim(strip_tags($h3->html()));

                    $a = $h3->filter('a')->first();

                    if ($a) {
                        $product['url'] = $a->attr('href');
                    }
                }
            }
            catch (\InvalidArgumentException $e) {};

            // SKU
            try {
                $btn = $item->filter('.ajax_add_to_cart_button')->first();

                if ($btn) {
                    $product['sku'] = 'PRODUCT_ID-' . trim($btn->attr('data-id-product'));
                }
            }
            catch (\InvalidArgumentException $e) {};

            if (!isset($product['sku'])) {
                $parts = explode('/', $product['url']);
                $productId = (int)end($parts);

                $product['sku'] = 'PRODUCT_ID-' . $productId;
            }

            // Image
            try {
                $img = $item->filter('img');

                if ($img) {
                    $product['images'][] = trim($img->attr('src'));
                }
            }
            catch (\InvalidArgumentException $e) {};

            // Price
            $priceData = [];
            $priceData['currency'] = 'EUR';

            try {
                $price = $item->filter('.right-price-cont .availability')->first();

                if ($price) {
                    $priceData['price'] = (float)str_replace([' ', '€', 'TTC'], '', $price->text());

                    $product['in_stock'] = ProductStatus::$IN_STOCK;
                }
            }
            catch (\InvalidArgumentException $e) {};

            if (!isset($priceData['price'])) {
                $priceData['price'] = 0;

                $product['in_stock'] = ProductStatus::$OUT_OF_STOCK;
            }

            $product['price'] = $priceData;


            $results[] = $product;
        });

        return $results;
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function canHandle(Document $document): bool
    {
        return strpos($document->getDocumentDomain(), static::$domain) !== false
            && strpos($document->getContent(), 'id="product_list"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return false;
    }
}