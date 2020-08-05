<?php

namespace App\Parsers\StoreParsers\NotikParsers;

use App\Crawler\Extractors\ProductExtractor;
use App\Crawler\Document;
use App\Domain;
use App\Exceptions\DomainNotFoundException;
use App\Exceptions\StoreNotFoundException;
use App\Parsers\ParserInterface;
use App\Store;
use Symfony\Component\DomCrawler\Crawler;

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
            && strpos($document->getContent(), 'class="productInfoBox"') !== false;
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
        return $this->content->filter('div.pathBox>span>a>span')->eq(1)->text();
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
        $descHtml = $this->content->filter('li.characteristics.active.cn-pth')->html();

        $removeTrashPattern = '/<table.width=.100%.>(.+?)<\/table>|<br>|<div.class=.parametersInCard.contanier.>(.+?)<\/a><\/div>|<\/div><\/td>(.+?)<\/a><\/div>||<div.title=(.+?)<\/span><\/div>/ui';
        $desc = preg_replace($removeTrashPattern, '', $descHtml);

        return strip_tags($desc);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $html = $this->content->html();

        $keysPattern = '/<td.class=.cell1.>(<[^>]*>|<[^>]*><[^>]*>)([а-яА-ЯёЁ]|[a-zA-Z]|\s|,|\.)+/iu';
        $valuesPattern = '/<\/td><td>(<img[^>]+?jpg.*?>|\+|®|<b>|<\/?br\/?>|<span>|<\/span>|[a-zA-Z]|\s|\d|[а-яА-ЯёЁ]|-|\.|\"|\'|\(|\)|,|:|\/|±|=|_|<a|>)+/u';

        $priceValuePattern = '/<noindex><b><[^>]*>(\d|\W)+/u';
        preg_match($priceValuePattern, $html, $match);
        $priceValue = preg_replace('/([^\d])/', '', $match[0]);

        $validKeys = [
            //notebooks, monoblocks
            'Процессор', 'Количество ядер', 'Кэш', 'Оперативная память', 'Экран', 'Разрешение', 'Видеокарта',
            'Звук', 'Накопитель', 'Связь', 'Беспроводная связь', 'Порты', 'Слоты расширения', 'Дополнительные устройства',
            'Устройства ввода', 'Дополнительно', 'Цвет', 'Цвет клавиатуры', 'Материал корпуса', 'Материал крышки',
            'Размеры корпуса', 'Вес', 'Батарея', 'Операционная система', 'Гарантия', 'Партнам', 'Артикул', 'Цена',
            'Комплектация', 'Оптический привод',
            //monitors
            'Производитель', 'Серия', 'Модель', 'Интерфейсы', 'Диагональ ', 'Поверхность экрана', 'Соотношение сторон',
            'Разрешение, пикс.', 'Стандарт разрешения', 'Тип матрицы', 'Контрастность', 'Динамическая контрастность',
            'Частота развертки', 'Время отклика', 'Яркость экрана', 'Размер крепления VESA', 'Размеры, мм',
            //pads
            'Встроенная память', 'Задняя камера', 'Фронтальная камера', 'Датчики', 'Порт зарядки',
            'Программное обеспечение',
            //smartphones
            'Особенности камер', 'Время работы', 'Степень защиты',
        ];

        preg_match_all($keysPattern, $html, $matches);
        $keys = preg_replace('/<[^>]*>|\[\s\?\s\]|:/', '', $matches[0]);

        preg_match_all($valuesPattern, $html, $matches);
        $values = preg_replace('/<br>/', ' ', $matches[0]); //remove <br> tag between 2 video cards
        $values = preg_replace('/<[^>]*>|\[\s\?\s\]/', '', $values);

        $attrs = [];

        foreach ($keys as $key) {
            if (in_array($key, $validKeys)) {
                $key = trim($key);
                array_push($attrs, $key);
            }
        }

        $values = array_splice($values, 0, count($attrs));
        $attrs = array_combine($attrs, $values);
        $attrs["Цена"] = $priceValue;

        return $attrs;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        $price = $this->content->filter('span.product-price')->first();

        if ($price) {
            return (float)preg_replace('/[^0-9]/', '', $this->clear($price->text()));
        }

        throw new \InvalidArgumentException("Can't recognize a price.");
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return 'RUB';
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
    public function getOldPrice(): float
    {
        return 0.0;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        $sku = $this->content->filter('div.artBox')->first();

        if ($sku) {
            return preg_replace('/[^0-9]/', '', $this->clear($sku->text()));
        }

        throw new \InvalidArgumentException("Can't recognize SKU.");
    }

    /**
     * @return bool
     */
    public function getIsAvailable(): bool
    {
        return $this->content->filter('div.inSight')->count() > 0;
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
        $benefits = [];

        if ($this->content->filter('div.priceCartBoxUnder > div')->count() > 0) {
            $this->content->filter('div.priceCartBoxUnder > div')->each(function (Crawler $div) use (&$benefits) {
                $divContent = $div->html();

                if (strpos($divContent, "Получите") !== false) {
                    $bonus = $div->filter('b')->first();

                    if ($bonus) {
                        $benefits['bonus'] = (int)$bonus->html();
                    }
                }

                if (strpos($divContent, "delivery-today-icon-prodcard") !== false) {
                    $benefits['delivery_today'] = true;
                }
            });
        }

        return json_encode($benefits);
    }

    /**
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->content->filter('title')->first()->text();
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->content->filter('meta[name="description"]')->first()->attr('content');
    }

    /**
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->content->filter('meta[name="keywords"]')->first()->attr('content');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->content->filter('link[rel="canonical"]')->first()->attr('href');
    }
}
