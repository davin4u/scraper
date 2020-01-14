<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Parsers\Document;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DnsShopProductPageParser
 * @package App\Parsers\StoreParsers\DnsShopParsers
 */
class DnsShopProductPageParser implements ParserInterface
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
     * @param $content
     * @return mixed
     */
    public function handle($content)
    {
        $product = [];
        $data = [];

        $page = (new Crawler($content));

        $sku = $page->filter('.price-item-code')->first();

        if ($sku) {
            preg_match('/.+(\d+?).+/siU', $sku->html(), $matches);

            $product['sku'] = isset($matches[1]) ? $matches[1] : null;
        }

        $rating = $page->filter('.product-item-rating')->first();

        if ($rating) {
            $product['store_rating'] = $rating->attr('data-rating');
        }

        $votes = $page->filter("[itemprop='ratingCount']")->first();

        if ($votes) {
            $product['votes_count'] = trim(strip_tags($votes->html()));
        }

        (new Crawler($content))->filter('#main-characteristics table tr')->each(function (Crawler $tr) use (&$data) {
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

            $data[$propName] = $value;
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
