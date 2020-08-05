<?php

namespace App\Parsers\StoreParsers\OtzovikParsers;

use App\Crawler\Document;
use App\Crawler\Extractors\ReviewExtractor;
use App\Parsers\ParserInterface;
use Carbon\Carbon;

/**
 * Class OtzovikReviewPageParser
 * @package App\Parsers\StoreParsers\OtzovikParsers
 */
class OtzovikReviewPageParser extends ReviewExtractor implements ParserInterface
{
    /**
     * @var string
     */
    protected static $domain = 'otzovik.com';

    /**
     * @param Document $document
     * @return bool
     */
    public static function canHandle(Document $document): bool
    {
        return strpos(static::$domain, $document->getDocumentDomain()) !== false
            && strpos($document->getContent(), 'class="produkts-content hreview"') !== false;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->content->filter('a.permalink')->extract(['href'])[0];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->content->filter('h1')->first()->text();
    }

    /**
     * @return Carbon
     */
    public function getPublishedAt(): Carbon
    {
        return Carbon::parse($this->content->filter('abbr.value')->extract(['title'])[0]);
    }

    /**
     * @return string
     */
    public function getPros(): string
    {
        return $this->content->filter('div.review-plus')->text();
    }

    /**
     * @return string
     */
    public function getCons(): string
    {
        return $this->content->filter('div.review-minus')->text();
    }

    /**
     * @return int
     */
    public function getReviewLikesCount(): int
    {
        $html = $this->content->filter('span.review-btn.review-yes')->text();
        $likesCount = explode(':', $html);

        return (int)$likesCount[1];
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        $tagsAndAdsPattern = '/<[^>]*>|\(ads.+?\);|\n+|\s{2,}/u';
        $html = $this->content->filter('div.review-body.description')->html();

        return preg_replace($tagsAndAdsPattern, '', $html);
    }

    /**
     * @return string
     */
    public function getShortSummary(): string
    {
        return $this->content->filter('i.summary')->text();
    }

    /**
     * @return Carbon
     */
    public function getBoughtAt(): Carbon
    {
        $datePattern = '/<td>(\d+)<\/td>/u';
        $html = $this->content->filter('table.product-props>tbody')->html();
        preg_match($datePattern, $html, $match);

        return Carbon::createSafe((int)$match[1], 1, 1);
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        $rating = $this->content->filter('div.product-rating.tooltip-right')->extract(['title'])[0];
        $rating = (float)explode(':', $rating)[1];

        return $rating;
    }

    /**
     * @return bool
     */
    public function getIRecommend(): bool
    {
        $recommend = $this->content->filter('td.recommend-ratio')->html();
        $recommend = preg_replace('/<a(.+?)<\/a>/u', '', $recommend);
        $result = '';

        if ($recommend == 'ДА') {
            $result = true;
        }
        if ($recommend == 'НЕТ') {
            $result = false;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getReviewAuthorName(): string
    {
        return $this->content->filter('a.user-login>span')->text();
    }

    /**
     * @return string
     */
    public function getReviewAuthorProfileUrl(): string
    {
        return static::$domain . $this->content->filter('a.user-login')->extract(['href'])[0];
    }

    /**
     * @return array
     */
    public function getAdditionalRatings(): array
    {
        $titlesPattern = '/title="(.+)"/u';
        $html = $this->content->filter('div.product-rating-details')->html();
        $ratings = [];

        preg_match_all($titlesPattern, $html, $matches);

        foreach ($matches[1] as $match) {
            $ratings[explode(':', $match)[0]] = (float)explode('из', explode(':', $match)[1])[0];
        }

        return $ratings;
    }
}
