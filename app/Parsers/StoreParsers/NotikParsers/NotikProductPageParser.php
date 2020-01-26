<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\ParserInterface;

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
        // @TODO currently we don't really need to process a product page because we are focused on prices. Maybe later.
        return [];
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
