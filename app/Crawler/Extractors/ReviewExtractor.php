<?php

namespace App\Crawler\Extractors;

use App\Crawler\Extractor;
use App\Crawler\Interfaces\Matchable;
use App\Crawler\Matchers\SimpleReviewAuthorMatcher;
use Carbon\Carbon;

/**
 * Class ReviewExtractor
 * @package App\Crawler\Extractors
 */
abstract class ReviewExtractor extends Extractor
{
    /**
     * @var Matchable
     */
    protected $reviewAuthorMatcher;

    /**
     * ReviewExtractor constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        parent::__construct($content);

        $this->reviewAuthorMatcher = $this->getReviewAuthorMatcher();
    }

    /**
     * @return Matchable
     */
    protected function getReviewAuthorMatcher(): Matchable
    {
        return new SimpleReviewAuthorMatcher();
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return parse_url($this->clear($this->getUrl()), PHP_URL_HOST);
    }

    /**
     * @return int
     */
    public function getReviewAuthorId(): int
    {
        return $this->reviewAuthorMatcher->match(
            $this->clear($this->getReviewAuthorName()),
            [
                'platform' => $this->getPlatform(),
                'profile_url' => $this->clear($this->getReviewAuthorProfileUrl())
            ]
        );
    }

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
     * @return int
     */
    abstract public function getReviewLikesCount(): int;

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
     * @return string
     */
    abstract public function getReviewAuthorName(): string;

    /**
     * @return string
     */
    abstract public function getReviewAuthorProfileUrl(): string;

    /**
     * @return array
     */
    public function handle()
    {
        return $this->validate([
            'author_id'    => $this->getReviewAuthorId(),
            'title'        => $this->clear($this->getTitle()),
            'url'          => $this->clear($this->getUrl()),
            'published_at' => $this->getPublishedAt()->toDateString(),
            'pros'         => $this->clear($this->getPros()),
            'cons'         => $this->clear($this->getCons()),
            'likes_count'  => $this->getReviewLikesCount(),
            'body'         => $this->getBody(),
            'summary'      => $this->clear($this->getShortSummary()),
            'bought_at'    => $this->getBoughtAt()->toDateString(),
            'rating'       => $this->getRating(),
            'i_recommend'  => $this->getIRecommend() ? 1 : 0
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validate(array $data): array
    {
        $data = array_filter($data, function ($item) {
            return !empty($item);
        });

        return $data;
    }
}