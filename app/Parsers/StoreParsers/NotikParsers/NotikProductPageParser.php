<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Crawler\Extractors\ProductExtractor;
use App\Crawler\Document;
use App\Parsers\ParserInterface;

/**
 * Class NotikProductPageParser
 * @package App\Parsers\StoreParsers\NotikParsers
 */
class NotikProductPageParser extends ProductExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'www.notik.ru';

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
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->content->filter('.goodtitlemain')->text();
    }

    /**
     * @return string
     */
    public function getBrandName(): string
    {
        return $this->content->filter('div.pathBox>span>a>span')->eq(2)->text();
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->content->filter('div.pathBox>span>a>span')->first()->text();
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        $links = $this->content->filter('div.images-scroll-list.cn-pth.product-pictures-scroll-list-zone>ul>li>a')->extract(['href']);

        array_walk($links, function (&$value) {
            $value = "https://www.notik.ru$value";
        });

        return $links;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $desc = $this->content->filter('li.characteristics.active.cn-pth>div')->first()->text();
        $desc = preg_replace('/(.+)\.\.\./', '', $desc);

        return $desc;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $html = $this->content->html();

        $keysPattern = '/<td.class=.cell1.>(<[^>]*>|<[^>]*><[^>]*>)([а-яА-Я]|\s)+/iu';
        $manufacturerIdKeyPattern = '/<td.class=.cell1.><[^>]*>([а-яА-Я]|\s)+/iu';
        $valuesPattern = '/<\/td><td>(<b>|<\/?br\/?>|<span>|<\/span>|[a-zA-Z]|\s|\d|[а-яА-Я]|-|\.|\"|\' | \( | \) |,|:|\/)+/u';

        preg_match_all($keysPattern, $html, $matches);
        $keys = array_slice($matches[0], 0, 25);
        $keys = preg_replace('/<[^>]*>|\[\s\?\s\]|:/', '', $keys);

        preg_match_all($valuesPattern, $html, $matches);
        $values = array_slice($matches[0], 0, 25);
        $values = preg_replace('/<[^>]*>|\[\s\?\s\]|:|\s$/', '', $values);

        return array_combine($keys, $values);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        // TODO: Implement getPrice() method.
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        // TODO: Implement getCurrency() method.
    }
}
