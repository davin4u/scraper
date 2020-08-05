<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Crawler\Extractors\ProductExtractor;
use App\Crawler\Document;
use App\Parsers\ParserInterface;

class DnsShopCategoryParser extends ProductExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /*
    public function handle(string $content)
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
                $item['url'] = 'https://www.dns-shop.ru' . $link->attr('href');
            }

            $sku = $product->filter('.product-info__code')->first();

            if ($sku) {
                preg_match('/.+(\d+?).+/siU', $sku->html(), $matches);

                $item['sku'] = isset($matches[1]) ? $matches[1] : null;
            }

            $brand = $product->filter('i[data-product-param="brand"]')->first();

            if ($brand) {
                $item['brand_id'] = app()->make(BrandMatcher::class)->match(
                    $brand->attr('data-value')
                );
            }

            $price = $product->filter('.product-price__current')->first();

            try {
                if ($price) {
                    $item['price'] = (float)preg_replace('/[^0-9]/', '', trim(strip_tags($price->html())));
                }
            }
            catch (\InvalidArgumentException $e) {
                $item['price'] = 0;
            }

            $item['currency'] = 'RUB';

            $rating = $product->filter('.product-info__rating')->first();

            try {
                if ($rating) {
                    $item['store_rating'] = (float)$rating->attr('data-rating');
                }
            }
            catch (\InvalidArgumentException $e) {
                $item['store_rating'] = 0;
            }

            $results[] = $item;
        });

        return $results;
    }*/

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
        return false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return string
     */
    public function getBrandName(): string
    {
        // TODO: Implement getBrandName() method.
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        // TODO: Implement getCategoryName() method.
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        // TODO: Implement getPhotos() method.
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
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

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        // TODO: Implement getStoreId() method.
    }

    /**
     * @return float
     */
    public function getOldPrice(): float
    {
        // TODO: Implement getOldPrice() method.
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        // TODO: Implement getSku() method.
    }

    /**
     * @return bool
     */
    public function getIsAvailable(): bool
    {
        // TODO: Implement getIsAvailable() method.
    }

    /**
     * @return string
     */
    public function getDeliveryText(): string
    {
        // TODO: Implement getDeliveryText() method.
    }

    /**
     * @return string
     */
    public function getDeliveryDays(): string
    {
        // TODO: Implement getDeliveryDays() method.
    }

    /**
     * @return float
     */
    public function getDeliveryPrice(): float
    {
        // TODO: Implement getDeliveryPrice() method.
    }

    /**
     * @return string
     */
    public function getBenefits(): string
    {
        // TODO: Implement getBenefits() method.
    }

    /**
     * @return string
     */
    public function getMetaTitle(): string
    {
        // TODO: Implement getMetaTitle() method.
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        // TODO: Implement getMetaDescription() method.
    }

    /**
     * @return string
     */
    public function getMetaKeywords(): string
    {
        // TODO: Implement getMetaKeywords() method.
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        // TODO: Implement getUrl() method.
    }
}
