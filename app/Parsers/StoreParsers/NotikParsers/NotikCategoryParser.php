<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Domain;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use App\ProductStatus;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NotikCategoryParser
 * @package App\Parsers\StoreParsers\NotikParsers
 */
class NotikCategoryParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'notik.ru';

    /**
     * @var bool
     */
    protected $isSinglePageParser = false;

    /**
     * @param string $content
     * @return array|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(string $content)
    {
        $results = [];

        $domain = Domain::where('name', static::$domain)->first();

        $page = new Crawler($content);

        try {
            $category = $page->filter('h1');

            if ($category) {
                $category = $category->first();
            }
        }
        catch (\InvalidArgumentException $e) {
            $category = null;
        }

        $categoryId = $category
                            ? app()->make(CategoryMatcher::class)->match(
                                    trim(strip_tags($category->html()))
                            )
                            : null;

        $page->filter('.goods-list-grouped-table tr.hide-mob')->each(function (Crawler $tr, $trI) use (&$results, $domain, $categoryId) {
            $item = [];

            if (!is_null($domain)) {
                $item['domain_id'] = $domain->id;
            }

            if (!is_null($categoryId)) {
                $item['category_id'] = $categoryId;
            }

            // name and url
            try {
                $a = $tr->filter('a');

                if ($a->count() > 0) {
                    $a = $a->first();

                    $item['name'] = trim($a->html());
                    $item['url'] = 'https://www.notik.ru' . $a->attr('href');
                }
            }
            catch (\InvalidArgumentException $e) {}

            // manufacturer id
            try {
                $manufacturer = $tr->filter('.wordwrap')->first();

                if ($manufacturer) {
                    $item['manufacturer_id'] = trim($manufacturer->html());
                }
            }
            catch (\InvalidArgumentException $e) {}

            // sku
            try {
                $sku = $tr->filter('.artikul');

                if ($sku) {
                    $item['sku'] = trim(strip_tags($sku->html()));
                }
            }
            catch (\InvalidArgumentException $e) {}

            // in stock
            // @TODO doesn't work, check later
            try {
                $inStock = $tr->filter('div.available')->first();

                if ($inStock) {
                    $title = trim($inStock->attr('title'));

                    if (mb_strpos($title, 'Есть в наличии') !== false) {
                        $item['in_stock'] = ProductStatus::$IN_STOCK;
                    }
                    else if (mb_strpos($title, 'Осталось мало') !== false) {
                        $item['in_stock'] = ProductStatus::$LOW;
                    }
                    else if (mb_strpos($title, 'Предзаказ') !== false) {
                        $item['in_stock'] = ProductStatus::$PRE_ORDER;
                    }
                    else {
                        $item['in_stock'] = ProductStatus::$OUT_OF_STOCK;
                    }
                }
            }
            catch (\InvalidArgumentException $e) {}

            $results[$trI] = $item;
        });

        $page->filter('.goods-list-grouped-table tr.goods-list-table')->each(function(Crawler $tr, $trI) use (&$results) {
            $td = $tr->filter('td.gltc-cart');

            if ($td) {
                $a = $td->filter('a');

                if ($a->count() > 0) {
                    $a = $a->first();

                    // price
                    $results[$trI]['price'] = (float)trim($a->attr('ecprice'));
                    $results[$trI]['currency'] = 'RUB';

                    // brand
                    $results[$trI]['brand_id'] = app()->make(BrandMatcher::class)->match(trim($a->attr('ecbrand')));
                }
            }
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
                && strpos($document->getContent(), 'class="paginator align-left"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return $this->isSinglePageParser;
    }
}
