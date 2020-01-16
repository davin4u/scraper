<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Parsers\Document;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;
use App\Domain;

class DnsShopCategoryParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @var bool
     */
    protected $singlePageParser = false;

    /**
     * @param string $content
     * @return array|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($content)
    {
        $results = [];

        $domain = Domain::where('name', static::$domain)->first();

        $category = (new Crawler($content))->filter('h1.title')->first();

        $categoryId = $category ? app()->make(CategoryMatcher::class)->match(trim($category->text())) : null;

        (new Crawler($content))->filter('div.catalog-item')->each(function (Crawler $product) use (&$results, $domain, $categoryId) {
            $item = [];

            if (!is_null($domain)) {
                $item['domain_id'] = $domain->id;
            }

            $item['category_id'] = $categoryId;

            $link = $product->filter('a.ui-link')->first();

            if ($link) {
                $item['name'] = $link->text();
                $item['url'] = $link->attr('href');
            }

            $sku = $product->filter('.product-info__code')->first();

            if ($sku) {
                preg_match('/.+(\d+?).+/siU', $sku->html(), $matches);

                $item['sku'] = isset($matches[1]) ? $matches[1] : null;
            }

            $brand = $product->filter('i[data-product-param="brand"]')->first();

            if ($brand) {
                $item['brand_id'] = BrandMatcher::match($brand->attr('data-value'));
            }

            $price = $product->filter('.product-price__current')->first();

            if ($price) {
                $item['price'] = (float)preg_replace('/[^0-9]/', '', trim(strip_tags($price->html())));
                $item['currency'] = 'RUB';
            }

            $rating = $product->filter('.product-info__rating')->first();

            if ($rating) {
                $item['store_rating'] = (float)$rating->attr('data-rating');
            }

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
        $domain = $document->getDocumentDomain();

        if (strpos(static::$domain, $domain) !== false) {
            $content = $document->getContent();

            return strpos($content, 'products-page__list') !== false;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return $this->singlePageParser;
    }
}
