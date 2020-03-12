<?php

namespace App\Parsers\StoreParsers\ProfmbroadcastParsers;

use App\Domain;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class ProfmbroadcastCategoryParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.profmbroadcast.com';

    /**
     * @param string $content
     * @return mixed|void
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
            $category = $page->filter('h2.text_grey .text_darkblue');

            if ($category) {
                $categoryName = trim(strip_tags($category->html()));
            }
        }
        catch (\InvalidArgumentException $e) {};

        if (!is_null($categoryName) && $categoryName) {
            $categoryId = app()->make(CategoryMatcher::class)->match($categoryName);
        }

        $page->filter('.feat_products > a.tile_product')->each(function (Crawler $product) use (&$results, $categoryId, $domain) {
            $item = [];

            $item['domain_id'] = $domain->id;
            $item['category_id'] = $categoryId;
            $item['url'] = 'https://' . static::$domain . $product->attr('href');

            // SKU
            $urlParts = explode('/', $product->attr('href'));

            $item['sku'] = 'PRODUCT_ID-' . $urlParts[2];

            // Image
            try {
                $img = $product->filter('.tile-img')->first();

                if ($img) {
                    $item['images'][] = 'https://' . static::$domain . $img->attr('data-bg');
                }
            }
            catch (\InvalidArgumentException $e) {};

            // Name
            try {
                $title = $product->filter('.tile-title')->first();

                if ($title) {
                    $text = $title->html();
                    $text = preg_replace('/\<div class="tile-price"\>(.+)\<\/div\>/siU', '', $text);

                    $item['name'] = trim(strip_tags($text));
                }
            }
            catch (\InvalidArgumentException $e) {};

            // Price
            $priceData = [];
            $priceData['currency'] = 'EUR';

            try {
                $price = $product->filter('.tile-title .tile-price')->first();

                if ($price) {
                    $text = $price->html();

                    if (strpos($text, '<span>') !== false) {
                        // contains "old" price

                        preg_match('/\<span\>(.+)\<\/span\>/siU', $price->html(), $matches);

                        if (isset($matches[1])) {
                            $priceData['old_price'] = (float) trim(str_replace('€', '', $matches[1]));
                        }

                        $text = preg_replace('/\<span\>(.+)\<\/span\>/siU', '', $text);
                    }

                    if (strpos($text, 'On request')) {
                        $priceData['price'] = 0;
                    }
                    else {
                        $priceData['price'] = (float) trim(str_replace('€', '', $text));
                    }
                }
            }
            catch (\InvalidArgumentException $e) {};

            $item['price'] = $priceData;

            $results[] = $item;
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
            && strpos($document->getContent(), 'tiles clearfix feat_products') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return false;
    }
}