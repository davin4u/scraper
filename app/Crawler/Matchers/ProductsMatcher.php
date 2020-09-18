<?php


namespace App\Crawler\Matchers;


use App\Product;

class ProductsMatcher
{
    public function match(int $categoryId, array $params)
    {
        $matches = [];
        $matchPercent = [];
        $gradation = [
            'Оперативная память' => 100.0,
            'Количество ядер' => 80.0,
            'Кэш' => 70.0,
            'Процессор' => 50.0,
            'Цена' => 40.0,
            'Вес' => 10.0,
            //for monitors
            'Диагональ' => 60,
            'Время отклика' => 50.0,
            'Частота развертки' => 40.0,
            'Яркость экрана' => 30.0,
            'Звук' => 10.0,
        ];

        Product::query()->where('category_id', $categoryId)->chunk(5000,
            function ($products) use ($params, $gradation, &$matches, &$matchPercent) {
                foreach ($products as $product) {
                    $matchPercent[$product->id] = 0.0;
                    $matches[$product->id] = $product;
                    foreach ($product->attributes as $attribute) {
                        if (array_key_exists($attribute->name, $params)) {
                            if (array_key_exists($attribute->name, $gradation)) {
                                $matchPercent[$product->id] += $this->checkGradationAttribute($attribute->name, $attribute->attributeValue->value, $params, $gradation);
                            }
                            if (strpos(strtolower($attribute->attributeValue->value), strtolower($params[$attribute->name])) !== false && !array_key_exists($attribute->name, $gradation)) {
                                $matchPercent[$product->id] += $this->checkStringAttribute($attribute->attributeValue->value, $params[$attribute->name]);
                            }
                        }
                    }
                }
            });

        return $this->getProducts($matches, $matchPercent);
    }

    protected function checkStringAttribute($attrValue, $paramValue): float
    {
        similar_text($paramValue, $attrValue, $percent);

        return $percent;
    }

    protected function checkGradationAttribute($attrName, $attrValue, array $params, array $gradationArray): float
    {
        $counter = 0.0;
        $explodedVars = [
            'Кэш',
            'Оперативная память',
            'Вес',
            'Диагональ',
            'Время отклика',
            'Частота развертки',
            'Яркость экрана',
            'Звук'
        ];

        if ($attrName == 'Процессор') {
            $counter += $this->checkStringAttribute($attrValue, $params[$attrName]);
        }
        if ($attrName == 'Количество ядер' || $attrName == 'Цена') {
            if (floatval($params[$attrName]) != floatval($attrValue)) {
                $counter -= $gradationArray[$attrName];
            } else {
                $counter += $gradationArray[$attrName];
            }
        }
        if (in_array($attrName, $explodedVars)) {
            $inBaseValue = floatval(explode(' ', $attrValue)[0]);
            if (floatval($params[$attrName]) != $inBaseValue) {
                $counter -= $gradationArray[$attrName];
            } else {
                $counter += $gradationArray[$attrName];
            }
        }

        return $counter;
    }

    protected function getProducts(array $products, array $matches): array
    {
        asort($matches, SORT_NUMERIC);
        $matches = array_reverse($matches, true);

        return array_replace($matches, $products);
    }
}
