<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class NotikProductPageParser
 * @package App\Parsers\StoreParsers\NotikParsers
 */
class NotikProductPageParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'notik.ru';

    /**
     * @var bool
     */
    protected $isSinglePageParser = true;

    /**
     * @param string $content
     * @return mixed
     */
    public function handle(string $content)
    {
        $product = [];

        $page = new Crawler($content);

        // Meta title
        try {
            $title = $page->filter('title')->first();

            if ($title) {
                $product['meta_title'] = trim($title->html());
            }
        }
        catch (\InvalidArgumentException $e) {}

        // Meta description
        try {
            $description = $page->filter('meta[name="description"]');

            if ($description && $description->count()) {
                $product['meta_description'] = trim($description->attr('content'));
            }
        }
        catch (\InvalidArgumentException $e) {}

        // Meta keywords
        try {
            $keywords = $page->filter('meta[name="keywords"]');

            if ($keywords && $keywords->count()) {
                $product['meta_keywords'] = trim($keywords->attr('content'));
            }
        }
        catch (\InvalidArgumentException $e) {}

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
            && strpos($document->getContent(), 'class="productInfoBox"') !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return $this->isSinglePageParser;
    }
}
