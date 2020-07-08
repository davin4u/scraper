<?php

namespace App\Crawler\Extractors;

use App\Crawler\Exceptions\CrawlerValidationException;
use App\Crawler\Extractor;

/**
 * Class ProductExtractor
 * @package App\Crawler\Extractors
 */
abstract class ProductExtractor extends Extractor
{
    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getBrandName(): string;

    /**
     * @return string
     */
    abstract public function getCategoryName(): string;

    /**
     * @return array
     */
    abstract public function getPhotos(): array;

    /**
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * @return array
     */
    abstract public function getAttributes(): array;

    /**
     * @return float
     */
    abstract public function getPrice(): float;

    /**
     * @return string
     */
    abstract public function getCurrency(): string;

    /**
     * @return array
     * @throws CrawlerValidationException
     */
    public function handle()
    {
        $categoryId = (int)$this->matchCategory($this->clear($this->getCategoryName()));

        return $this->validate([
            'name' => $this->clear($this->getName()),
            'brand_id' => $this->matchBrand($this->clear($this->getBrandName())),
            'category_id' => $categoryId,
            'description' => $this->clear($this->getDescription()),
            'photos' => $this->getPhotos(),
            //'price' => $this->getPrice(),
            //'currency' => $this->clear($this->getCurrency()),
            'attributes' => $this->matchAttributes($this->getAttributes(), $categoryId)
        ]);
    }

    /**
     * @param array $data
     * @return array
     * @throws CrawlerValidationException
     */
    protected function validate(array $data): array
    {
        $data = array_filter($data, function ($item) {
            return !empty($item) && $item;
        });

        if (empty($data['name'])) {
            throw new CrawlerValidationException("name field is required");
        }

        if (empty($data['category_id']) || !$data['category_id']) {
            throw new CrawlerValidationException("category_id is required");
        }

        return $data;
    }
}