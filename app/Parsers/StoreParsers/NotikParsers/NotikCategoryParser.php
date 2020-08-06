<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Crawler\Extractors\ProductExtractor;
use App\Crawler\Document;
use App\Parsers\ParserInterface;

/**
 * Class NotikCategoryParser
 * @package App\Parsers\StoreParsers\NotikParsers
 */
class NotikCategoryParser extends ProductExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'notik.ru';

    /*
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

        $categoryName = $category ? trim(strip_tags($category->html())) : null;

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

        $page->filter('.goods-list-grouped-table tr.goods-list-table')->each(function(Crawler $tr, $trI) use (&$results, $categoryName, $categoryId) {
            $td = $tr->filter('td.gltc-cart');

            if ($td) {
                $a = $td->filter('a');

                if ($a->count() > 0) {
                    $a = $a->first();

                    // price
                    $results[$trI]['price'] = (float)trim($a->attr('ecprice'));
                    $results[$trI]['currency'] = 'RUB';

                    // brand
                    try {
                        $brandName = trim($a->attr('ecbrand'));
                        $results[$trI]['brand_id'] = app()->make(BrandMatcher::class)->match($brandName);
                    }
                    catch (\InvalidArgumentException $e) {}

                    // model
                    if (!is_null($categoryId) && !is_null($categoryName) && !empty($results[$trI]['name']) && isset($brandName)) {
                        $attr = $this->attributes->recognizeAttribute('Модель', $categoryId);

                        if (!is_null($attr)) {
                            $model = trim(str_replace($categoryName, '', $results[$trI]['name']));
                            $model = trim(str_replace($brandName, '', $model));

                            $results[$trI]['attributes'] = [
                                $attr->attribute_key => $model
                            ];
                        }
                    }
                }
            }
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
        return strpos(static::$domain, $document->getDocumentDomain()) !== false
                && strpos($document->getContent(), 'class="paginator align-left"') !== false;
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
     * @return string
     */
    public function getUrl(): string
    {
        // TODO: Implement getUrl() method.
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
}
