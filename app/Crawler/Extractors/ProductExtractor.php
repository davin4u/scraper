<?php

namespace App\Crawler\Extractors;

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
     * @return array
     */
    public function handle()
    {
        return [
            'name' => $this->clear($this->getName()),
            'brand' => $this->matchBrand($this->clear($this->getBrandName())),
            'category' => $this->matchCategory($this->clear($this->getCategoryName())),
            'description' => $this->clear($this->getDescription()),
            'photos' => $this->getPhotos(),
            'properties' => $this->getAttributes()
        ];
    }
}