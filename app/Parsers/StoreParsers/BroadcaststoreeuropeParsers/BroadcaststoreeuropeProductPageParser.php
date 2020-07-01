<?php

namespace App\Parsers\StoreParsers\BroadcaststoreeuropeParsers;

use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class BroadcaststoreeuropeProductPageParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'broadcaststoreeurope.com';

    /**
     * @param string $content
     * @return mixed
     */
    public function handle(string $content)
    {
        $product = [];
        $page = new Crawler($content);

        // domain
        $domain = $this->getDomain();

        if (!is_null($domain)) {
            $product['domain_id'] = $domain->id;
        }

        // name
        try {
            $h1 = $page->filter('h1.product-title')->first();

            if ($h1) {
                $product['name'] = trim($h1->text());
            }
        }
        catch (\InvalidArgumentException $e) {}

        // description
        try {
            $description = $page->filter('#tabs-pane1')->first();

            if ($description) {
                $product['description'] = str_replace('<br />', '\n',strip_tags($description->html(), '<br>'));
            }
        }
        catch (\InvalidArgumentException $e) {}

        // sku
        try {
            $sku = $page->filter('.m-product-itemNumber-value')->first();

            if ($sku) {
                $product['sku'] = trim($sku->text());
            }
        }
        catch (\InvalidArgumentException $e) {}

        if (empty($product['sku'])) {
            try {
                $nav = $page->filter('.nav-breadcrumbs>li')->last();

                if ($nav) {
                    $a = $nav->filter('a')->first();

                    if ($a) {
                        $parts = array_filter(explode("/", $a->attr("href")), function ($item) {
                            return strlen(trim($item)) > 0;
                        });

                        $id = (int)end($parts);

                        if ($id) {
                            $product['sku'] = 'PRODUCT_ID-' . $id;
                        }
                    }
                }
            }
            catch (\InvalidArgumentException $e) {}
        }

        return $product;
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function canHandle(Document $document): bool
    {
        return strpos(static::$domain, $document->getDocumentDomain()) !== false
            && strpos($document->getContent(), 'class="nav nav-tabs m-product-additional-info-tabs"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return true;
    }
}