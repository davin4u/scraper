<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DnsShopProductPageParser
 * @package App\Parsers\StoreParsers\DnsShopParsers
 */
class DnsShopProductPageParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @var bool
     */
    protected $singlePageParser = true;

    /**
     * @param string $content
     * @return array|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($content)
    {
        $product = [];
        $data = [];

        $page = (new Crawler($content));

        // SKU
        $sku = $page->filter('.price-item-code')->first();

        if ($sku) {
            preg_match('/.+(\d+?).+/siU', $sku->html(), $matches);

            $product['sku'] = isset($matches[1]) ? $matches[1] : null;
        }

        // Rating
        $rating = $page->filter('.product-item-rating')->first();

        if ($rating) {
            $product['store_rating'] = $rating->attr('data-rating');
        }

        // Votes count
        $votes = $page->filter("[itemprop='ratingCount']")->first();

        if ($votes) {
            $product['votes_count'] = trim(strip_tags($votes->html()));
        }

        // Category
        $categoryId = null;

        $category = $page->filter('[data-product-param="category"]');

        if ($category) {
            $categoryId = app()->make(CategoryMatcher::class)->match(
                trim($category->attr('data-value'))
            );

            $product['category_id'] = $categoryId;
        }

        // Brand
        $brand = $page->filter('[data-product-param="brand"]');

        if ($brand) {
            $brandId = app()->make(BrandMatcher::class)->match(
                trim($brand->attr('data-value'))
            );

            $product['brand_id'] = $brandId;
        }

        // Product attributes
        (new Crawler($content))->filter('#main-characteristics table tr')->each(function (Crawler $tr) use (&$data, $categoryId) {
            $td = $tr->filter('td');

            if ($td->count() < 2) {
                return;
            }

            $propName = trim(strip_tags($td->eq(0)->html(), '<span>'));

            preg_match('/\<span\>(.+)\<\/span\>/siU', $propName, $matches);
            if (isset($matches[1])) {
                $propName = trim($matches[1]);
            }
            unset($matches);

            $value = trim($td->eq(1)->html());

            if (strpos($value, 'popover-link hidden-xs') !== false) {
                preg_match('/.+\<a class="popover-link.+\>(.+)\<\/a.+/siU', $value, $matches);

                if (isset($matches[1])) {
                    $value = trim($matches[1]);
                }
            }

            $attribute = $this->attributes->recognizeAttribute($propName, $categoryId);

            $data[$attribute->attribute_key] = $value;
        });

        $product['attributes'] = $data;

        return $product;
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function canHandle(Document $document): bool
    {
        $domain = $document->getDocumentDomain();

        if (strpos(static::$domain, $domain) !== false) {
            $content = $document->getContent();

            return strpos($content, 'id="item-tabs-block"') !== false;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser() : bool
    {
        return $this->singlePageParser;
    }
}
