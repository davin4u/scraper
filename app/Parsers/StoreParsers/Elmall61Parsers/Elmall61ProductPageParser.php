<?php

namespace App\Parsers\StoreParsers\Elmall61Parsers;

use App\Crawler\Document;
use App\Crawler\Extractors\ProductExtractor;
use App\Domain;
use App\Exceptions\DomainNotFoundException;
use App\Exceptions\StoreNotFoundException;
use App\Parsers\ParserInterface;
use App\Store;
use Illuminate\Support\Arr;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Elmall61ProductPageParser
 * @package App\Parsers\StoreParsers\Elmall61Parsers
 */
class Elmall61ProductPageParser extends ProductExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'elmall61.ru';

    /**
     * @var int
     */
    protected static $storeId;

    /**
     * @param Document $document
     * @return bool
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function canHandle(Document $document): bool
    {
        return strpos(static::$domain, $document->getDocumentDomain()) !== false
            && strpos($document->getContent(), 'id="event_price_reduction"') !== false;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->content->filter('[rel="canonical"]')->attr('href');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->content->filter('#bbody h1')->text();
    }

    /**
     * @return string
     */
    public function getBrandName(): string
    {
        return $this->content->filter('#crumbs a')->last()->text();
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        $crumbsContent = $this->content->filter('#crumbs')->html();

        if ($crumbsContent) {
            preg_match_all('/\<a.+\>(.+)\<\/a\>/siU', $crumbsContent, $matches);

            if (!empty($matches[1])) {
                return Arr::get($matches[1], count($matches[1]) - 2, null);
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $description = '';

        $this->content->filter('#bbody > table > tr')->each(function (Crawler $tr, $trI) use (&$description) {
            if ($trI === 1) {
                $description = $tr->filter('td')->text();
            }
        });

        return $description;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws DomainNotFoundException
     * @throws StoreNotFoundException
     */
    public function getStoreId(): int
    {
        if (!static::$storeId) {
            $domain = Domain::where('name', static::$domain)->first();

            if (!$domain) {
                throw new DomainNotFoundException("Domain {$domain} not found.");
            }

            $store = Store::where('domain_id', $domain->id)->first();

            if (!$store) {
                throw new StoreNotFoundException("Store not found.");
            }

            static::$storeId = $store->id;
        }

        return static::$storeId;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->clear(html_entity_decode($this->content->filter('#product_price_rub')->text()));
    }

    /**
     * @return float
     */
    public function getOldPrice(): float
    {
        return 0.0;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return 'RUB';
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->content->filter('#product_id')->text();
    }

    /**
     * @return bool
     */
    public function getIsAvailable(): bool
    {
        $isAvailable = false;

        $this->content->filter('#bbody > table > tr')->each(function (Crawler $tr, $trI) use (&$isAvailable) {
            if ($trI === 3) {
                $isAvailable = strpos($tr->html(), 'Есть на складе') !== false;
            }
        });

        return $isAvailable;
    }

    /**
     * @return string
     */
    public function getDeliveryText(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getDeliveryDays(): string
    {
        return '';
    }

    /**
     * @return float
     */
    public function getDeliveryPrice(): float
    {
        return 0.0;
    }

    /**
     * @return string
     */
    public function getBenefits(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->content->filter('title')->text();
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->content->filter('meta[name="description"]')->attr('content');
    }

    /**
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->content->filter('meta[name="keywords"]')->attr('content');
    }
}