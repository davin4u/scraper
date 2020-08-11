<?php

namespace App\Parsers\StoreParsers\Elmall61Parsers;

use App\Crawler\Document;
use App\Crawler\Extractors\ProductsCategoryExtractor;
use App\Parsers\ParserInterface;
use Illuminate\Support\Arr;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Elmall61CategoryParser
 * @package App\Parsers\StoreParsers\Elmall61Parsers
 */
class Elmall61CategoryParser extends ProductsCategoryExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'elmall61.ru';

    /**
     * @param Document $document
     * @return bool
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function canHandle(Document $document): bool
    {
        preg_match_all('/[\'"].+elmall61.ru\/trade_list?.+[&|&amp;]on_page=(.+)[&|&amp;]?[\'"]/siU', $document->getContent(), $matches);

        return strpos(static::$domain, $document->getDocumentDomain()) !== false
            && isset($matches[1]) && count($matches[1]) > 0;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        $products = [];

        $categoryName = $this->clear($this->extractCategoryName());

        $this->content->filter('.trade_list_element')->each(function (Crawler $element) use (&$products, $categoryName) {
            $product = [];

            $product['sku'] = $this->clear($element->filter('.goods_article')->first()->text());

            if (!is_null($categoryName)) {
                $product['category'] = $categoryName;
            }

            $element->filter('table > tr')->each(function (Crawler $tr, $trI) use (&$product) {
                if ($trI === 0) {
                    $td = $tr->filter('td')->eq(1);

                    preg_match('/href=[\'"](.+)[\'"]\>(.+)\<\/a\>/siU', $td->html(), $matches);

                    if (!empty($matches[1])) {
                        $product['url'] = 'http://' . static::$domain . $this->clear($matches[1]);
                    }

                    if (!empty($matches[2])) {
                        $product['name'] = $this->clear($matches[2]);
                    }
                }

                if ($trI === 1) {
                    $product['price'] = (float)$this->clear($tr->filter('b')->first()->text());
                }
            });

            $products[] = $product;
        });

        return $products;
    }

    /**
     * @return mixed|null
     */
    private function extractCategoryName()
    {
        $crumbsContent = $this->content->filter('#crumbs')->html();

        if ($crumbsContent) {
            preg_match_all('/\<a.+\>(.+)\<\/a\>/siU', $crumbsContent, $matches);

            if (!empty($matches[1])) {
                return Arr::get($matches[1], count($matches[1]) - 1, null);
            }
        }

        return null;
    }
}