<?php

namespace App\Parsers\StoreParsers\TransmittersrusParsers;

use App\Domain;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class TransmittersrusCategoryParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.transmittersrus.com';

    /**
     * @param string $content
     * @return mixed|void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(string $content)
    {
        $results = [];

        $domain = Domain::where('name', static::$domain)->first();

        $page = new Crawler($content);

        try {
            $category = $page->filter('h1')->first();

            if ($category) {
                $category = trim($category->text());
            }
        }
        catch (\InvalidArgumentException $e) {
            $category = null;
        }

        $categoryId = $category ? app()->make(CategoryMatcher::class)->match($category) : null;

        $page->filter('.products.columns-4 > li')->each(function (Crawler $item) use (&$results, $categoryId, $domain) {
            $product = [];

            if (!is_null($domain)) {
                $product['domain_id'] = $domain->id;
            }

            if (!is_null($categoryId)) {
                $product['category_id'] = $categoryId;
            }

            // name
            try {
                $name = $item->filter('h2')->first();

                if ($name) {
                    $product['name'] = trim($name->text());
                }
            }
            catch (\InvalidArgumentException $e) {}

            // url
            try {
                $link = $item->filter('.woocommerce-LoopProduct-link')->first();

                if ($link) {
                    $product['url'] = trim($link->attr('href'));
                }
            }
            catch (\InvalidArgumentException $e) {}

            // brand
            try {
                $link = $item->filter('.wb-posted_in > a')->first();

                if ($link) {
                    $brand = app()->make(BrandMatcher::class)->match(trim(strip_tags($link->text())));

                    if ($brand) {
                        $product['brand_id'] = $brand;
                    }
                }
            }
            catch (\InvalidArgumentException $e) {}

            // sku
            try {
                $button = $item->filter('.add_to_cart_button')->first();

                if ($button) {
                    $product['sku'] = trim($button->attr('data-product_id'));
                }
            }
            catch (\InvalidArgumentException $e) {}

            if (empty($product['sku'])) {
                try {
                    $button = $item->filter('.product_type_grouped')->first();

                    if ($button) {
                        $product['sku'] = trim($button->attr('data-product_id'));
                    }
                }
                catch (\InvalidArgumentException $e) {}
            }

            // price
            $priceData['currency'] = 'USD';

            try {
                $price = $item->filter('.price')->first();

                if ($price) {
                    $amount = $price->filter('.woocommerce-Price-amount');

                    if ($amount) {
                        $priceValue = str_replace(['$', ','], '', trim(strip_tags($amount->last()->html())));

                        if ($priceValue) {
                            $priceData['price'] = (float) $priceValue;
                        }

                        if ($amount->count() === 2) {
                            $oldPriceValue = str_replace(['$', ','], '', trim(strip_tags($amount->first()->html())));

                            if ($oldPriceValue) {
                                $priceData['old_price'] = (float) $oldPriceValue;
                            }
                        }
                    }
                }
            }
            catch (\InvalidArgumentException $e) {}

            // the case when instead of price they have `Call for price`
            // we have to ensure that product data contains price value
            // so we set price to 0
            if (empty($priceData['price'])) {
                $priceData['price'] = 0;
            }

            $product['price'] = $priceData;

            // images
            try {
                $image = $item->filter('.et_shop_image img')->first();

                if ($image) {
                    $product['images'][] = trim($image->attr('src'));
                }
            }
            catch (\InvalidArgumentException $e) {}

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
        return strpos(static::$domain, $document->getDocumentDomain()) !== false
            && strpos($document->getContent(), 'class="products columns-4"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return false;
    }
}