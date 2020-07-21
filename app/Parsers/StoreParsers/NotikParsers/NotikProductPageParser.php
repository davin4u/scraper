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

        $keysPattern = '/<td.class=.cell1.>(<[^>]*>|<[^>]*><[^>]*>)([а-яА-ЯёЁ]|[a-zA-Z]|\s|,|\.)+/iu';
        $valuesPattern = '/<\/td><td>(<img[^>]+?jpg.*?>|\+|®|<b>|<\/?br\/?>|<span>|<\/span>|[a-zA-Z]|\s|\d|[а-яА-ЯёЁ]|-|\.|\"|\'|\(|\)|,|:|\/)+/u';

        $priceValuePattern = '/<noindex><b><[^>]*>(\d|\W)+/u';
        preg_match($priceValuePattern, $html, $match);
        $priceValue = preg_replace('/([^\d])/', '', $match[0]);

        $validKeys = [
            //notebooks, monoblocks
            'Процессор', 'Количество ядер', 'Кэш', 'Оперативная память', 'Экран', 'Разрешение', 'Видеокарта',
            'Звук', 'Накопитель', 'Связь', 'Беспроводная связь', 'Порты', 'Слоты расширения', 'Дополнительные устройства',
            'Устройства ввода', 'Дополнительно', 'Цвет', 'Цвет клавиатуры', 'Материал корпуса', 'Материал крышки',
            'Размеры корпуса', 'Вес', 'Батарея', 'Операционная система', 'Гарантия', 'Партнам', 'Артикул', 'Цена',
            'Комплектация',
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
