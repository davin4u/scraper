<?php

namespace App\Crawler\Extractors;

use App\Crawler\Extractor;
use Carbon\Carbon;

/**
 * Class ReviewExtractor
 * @package App\Crawler\Extractors
 */
abstract class ReviewExtractor extends Extractor
{
    /**
     * @return string
     */
    abstract public function getUrl(): string;

    /**
     * @return string
     */
    abstract public function getTitle(): string;

    /**
     * @return Carbon
     */
    abstract public function getPublishedAt(): Carbon;

    /**
     * @return string
     */
    abstract public function getPros(): string;

    /**
     * @return string
     */
    abstract public function getCons(): string;

    /**
     * @return string
     */
    abstract public function getReviewLikesCount(): string;

    /**
     * @return string
     */
    abstract public function getBody(): string;

    /**
     * @return string
     */
    abstract public function getShortSummary(): string;

    /**
     * @return Carbon
     */
    abstract public function getBoughtAt(): Carbon;

    /**
     * @return float
     */
    abstract public function getRating(): float;

    /**
     * @return bool
     */
    abstract public function getIRecommend(): bool;

    /**
     * @return int
     */
    abstract public function getReviewAuthorId(): int;

    public function handle()
    {

    }
}