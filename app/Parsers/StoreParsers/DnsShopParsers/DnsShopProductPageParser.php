<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Crawler\Extractors\ProductExtractor;
use App\Domain;
use App\Parsers\BaseParser;
use App\Crawler\Document;
use App\Parsers\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DnsShopProductPageParser
 * @package App\Parsers\StoreParsers\DnsShopParsers
 */
class DnsShopProductPageParser extends ProductExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * Mapping for manufacturer id property
     * category ID => Attribute Key
     * @var array
     */
    protected $manufacturerAttributeMapping = [
        1 => 'attr_5',
        2 => 'attr_79'
    ];

    /*
    public function handle()
    {
        $product = [];
        $data = [];

        $page = (new Crawler($content));

        $product['domain_id'] = Domain::where('name', static::$domain)->first()->id;

        // SKU
        $sku = $page->filter('.price-item-code')->first();

        if ($sku && $sku->count()) {
            preg_match('/.+(\d+?).+/siU', $sku->html(), $matches);

            $product['sku'] = isset($matches[1]) ? $matches[1] : null;
        }

        $name = $page->filter('h1.page-title')->first();

        if ($name) {
            $product['name'] = trim($name->html());
        }

        // Rating
        $rating = $page->filter('.product-item-rating');

        if ($rating && $rating->count()) {
            $rating = $rating->first();

            $product['store_rating'] = $rating->attr('data-rating');
        }

        // Votes count
        $votes = $page->filter("[itemprop='ratingCount']");

        if ($votes && $votes->count()) {
            $votes = $votes->first();

            $product['votes_count'] = trim(strip_tags($votes->html()));
        }

        // Category
        $categoryId = null;

        $breadcrumbs = $page->filter(".breadcrumb")->first();

        if ($breadcrumbs) {
            $breadcrumbs = $breadcrumbs->filter('li');

            if ($breadcrumbs && $breadcrumbs->count()) {
                $categoryName = $breadcrumbs->eq($breadcrumbs->count() - 2)->html();

                preg_match('/\<span.+\>(.+)\<\/span\>/siU', $categoryName, $matches);
                if (isset($matches[1])) {
                    $categoryId = app()->make(CategoryMatcher::class)->match(trim($matches[1]));
                }
                unset($matches);
            }
        }

        if (!is_null($categoryId)) {
            $product['category_id'] = $categoryId;
        }

        // Brand
        $brand = $page->filter('[data-product-param="brand"]');

        if ($brand && $brand->count()) {
            $brandId = app()->make(BrandMatcher::class)->match(
                trim($brand->attr('data-value'))
            );

            $product['brand_id'] = $brandId;
        }

        // Meta title
        $title = $page->filter('meta[property="og:title"]');

        if ($title && $title->count()) {
            $product['meta_title'] = trim($title->attr('content'));
        }

        // Meta description
        $description = $page->filter('meta[name="description"]');

        if ($description && $description->count()) {
            $product['meta_description'] = trim($description->attr('content'));
        }

        // Image
        $images = $page->filter('.owl-wrapper .owl-item');

        try {
            if ($images && $images->count()) {
                $image = $images->first();

                $img = $image->filter('img');
                if ($img) {
                    $product['images'] = [$img->attr('src')];
                }
            }
        }
        catch(\InvalidArgumentException $e) {
            $product['images'] = [];
        }

        // Product attributes
        (new Crawler($content))->filter('#main-characteristics table tr')->each(function (Crawler $tr) use (&$data, $categoryId) {
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

            $attribute = $this->attributes->recognizeAttribute($propName, $categoryId);

            $data[$attribute->attribute_key] = $value;
        });

        $product['attributes'] = $data;

        // manufacturer id
        if (
            !is_null($categoryId)
            && isset($this->manufacturerAttributeMapping[$categoryId])
            && !empty($product['attributes'][$this->manufacturerAttributeMapping[$categoryId]])
        ) {
            $product['manufacturer_id'] = $product['attributes'][$this->manufacturerAttributeMapping[$categoryId]];
        }

        return $product;
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

            return strpos($content, 'id="item-tabs-block"') !== false;
        }

        return false;
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
