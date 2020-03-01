<?php

namespace App\Parsers\StoreParsers\BroadcaststoreeuropeParsers;

use App\Domain;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\Parsers\ParserInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

/**
 * Class BroadcaststoreeuropeCategoryParser
 * @package App\Parsers\StoreParsers\BroadcaststoreeuropeParsers
 */
class BroadcaststoreeuropeCategoryParser extends BaseParser implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'broadcaststoreeurope.com';

    /**
     * @var array
     */
    protected static $categoriesMap = [];

    /**
     * @var array
     */
    protected static $brandsMap = [];

    /**
     * @param string $content
     * @return mixed
     */
    public function handle(string $content)
    {
        if (empty(static::$categoriesMap) || empty(static::$brandsMap)) {
            $this->init();
        }

        $results = [];

        $json = json_decode($content, true);

        if (!empty($json['products']) && is_array($json['products'])) {
            $domain = Domain::where('name', static::$domain)->first();

            foreach ($json['products'] as $product) {
                $item = [];

                $item['domain_id'] = $domain->id;
                $item['name'] = $product['Title'] ?: null;
                $item['sku'] = $product['ItemNumber'] ?: 'PRODUCT_ID-' . $product['Id'];
                $item['category_id'] = isset(static::$categoriesMap[$product['CategoryId']]) ? static::$categoriesMap[$product['CategoryId']] : null;
                $item['brand_id'] = isset(static::$brandsMap[$product['ProducerId']]) ? static::$brandsMap[$product['ProducerId']] : null;
                $item['url'] = 'https://' . static::$domain . $product['Handle'];

                $item['price']['currency'] = 'EUR';

                if ((bool)Arr::get($product, 'CallForPrice', false)) {
                    $item['price']['price'] = 0;
                }
                else {
                    $item['price']['price'] = Arr::get($product, 'Prices.0.PriceMin', 0);
                    $item['price']['old_price'] = Arr::get($product, 'Prices.0.FullPriceMin', null);
                    $item['price']['with_vat'] = Arr::get($product, 'Prices.0.PriceMinWithVat', null);
                }

                $images = Arr::get($product, 'Images', []);

                if (!empty($images)) {
                    $item['images'] = array_map(function ($image) {
                        return 'https://' . static::$domain . $image;
                    }, $images);
                }

                $results[] = $item;
            }
        }

        return $results;
    }

    private function init()
    {
        $client = new Client();

        $url = 'https://broadcaststoreeurope.com/json/products?field=categoryId&filterGenerate=true&limit=48&page=1';

        try {
            $response = $client->request('GET', $url);

            $json = json_decode($response->getBody()->getContents(), true);

            $categories = Arr::get($json, 'filterMap.categories.data', []);
            $brands = Arr::get($json, 'filterMap.brands.data', []);

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    static::$categoriesMap[$category['id']] = app()->make(CategoryMatcher::class)->match($category['title']);
                }
            }

            if (!empty($brands)) {
                foreach ($brands as $brand) {
                    static::$brandsMap[$brand['id']] = app()->make(BrandMatcher::class)->match($brand['title']);
                }
            }
        }
        catch (\Exception $e) {}
    }

    /**
     * @param Document $document
     * @return bool
     */
    public static function canHandle(Document $document): bool
    {
        return strpos($document->getDocumentDomain(), static::$domain) !== false;
    }

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool
    {
        return false;
    }
}