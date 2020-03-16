<?php

namespace App\Parsers\StoreParsers\TransmittersrusParsers;

use App\Domain;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class TransmittersrusProductPageParser extends BaseParser implements ParserInterface
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
        $product = [];
        $page = new Crawler($content);

        $product['domain_id'] = Domain::where('name', static::$domain)->first()->id;

        // name
        try {
            $h1 = $page->filter('h1.main-model')->first();

            if ($h1) {
                $product['name'] = trim($h1->html());
            }
        }
        catch (\InvalidArgumentException $e) {}

        // sku
        preg_match('/\<input.+name="add-to-cart" value="(.+)".+\>/siU', $content, $matches);

        if (isset($matches[1])) {
            $product['sku'] = $matches[1];
        }

        // meta title
        try {
            $title = $page->filter('title')->first();

            if ($title) {
                $product['meta_title'] = $title->text();
            }
        }
        catch (\InvalidArgumentException $e) {}

        // meta description
        try {
            $description = $page->filter('meta[name="description"]');

            if ($description && $description->count()) {
                $product['meta_description'] = trim($description->attr('content'));
            }
        }
        catch (\InvalidArgumentException $e) {}

        $categoryId = null;

        try {
            $a = $page->filter('nav.woocommerce-breadcrumb > a')->eq(2);

            if ($a) {
                $categoryId = app()->make(CategoryMatcher::class)->match(trim($a->text()));
            }
        }
        catch (\InvalidArgumentException $e) {}

        if (!is_null($categoryId)) {
            $product['category_id'] = $categoryId;
        }

        // attributes
        $attributes = [];

        if (!is_null($categoryId)) {
            try {
                $tabs = $page->filter('.woocommerce-Tabs-panel--specification > ul');

                if ($tabs && $tabs->count() > 0) {
                    $tabs->each(function (Crawler $ul) use (&$attributes, $categoryId) {
                        $ul->filter('li')->each(function (Crawler $li) use (&$attributes, $categoryId) {
                            $liContent = $li->html();
                            $attrName  = '';

                            preg_match('/\<strong\>(.+)\<\/strong\>/siU', $liContent, $matches);

                            if (isset($matches[1])) {
                                $attrName = trim(str_replace(':', '', $matches[1]));
                            }

                            $attrValue = trim(preg_replace('/\<strong\>.+\<\/strong\>/siU', '', $liContent));

                            if (strlen($attrName) > 0 && strlen($attrValue) > 0) {
                                $attribute = $this->attributes->recognizeAttribute($attrName, $categoryId);

                                $attributes[$attribute->attribute_key] = $attrValue;
                            }
                        });
                    });
                }
            }
            catch (\InvalidArgumentException $e) {}
        }

        $product['attributes'] = $attributes;

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
            && strpos($document->getContent(), 'class="woocommerce-tabs wc-tabs-wrapper"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return true;
    }
}