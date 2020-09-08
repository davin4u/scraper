<?php


namespace App\Parsers\StoreParsers\WordstatParsers;


use App\Crawler\Document;
use App\Crawler\Extractor;
use App\Parsers\ParserInterface;
use App\SearchStatistic;
use Carbon\Carbon;

class WordstatSearchPageParser extends Extractor implements ParserInterface
{
    protected static $domain = 'https://wordstat.yandex.ru';

    public static function canHandle(Document $document): bool
    {
        return strpos($document->getContent(), 'class="b-wordstat-content__content"') !== false;
    }

    public function getLastUpdateDate(): \DateTime
    {
        $html = $this->content->filter('div.b-word-statistics__last-update')->text();
        $lastUploadDate = $this->clear(explode(':', $html)[1]);

        return Carbon::parse($lastUploadDate)->toDateTime();
    }

    public function getSearchedWords(): array
    {
        return $this->content->filter('a.b-link.b-phrase-link__link')->extract(['_text']);
    }

    public function getViewsPerMonth(): array
    {
        $viewsPerMonth = $this->content->filter('td.b-word-statistics__td.b-word-statistics__td-number')->extract(['_text']);
        $viewsPerMonth = preg_replace('/[^\d]/', '', $viewsPerMonth);

        return $viewsPerMonth;
    }

    public function getCombinedSearchResponse(): array
    {
        return array_combine($this->getSearchedWords(), $this->getViewsPerMonth());
    }

    public function handle()
    {
        foreach ($this->getCombinedSearchResponse() as $phrase => $amount) {
            SearchStatistic::create([
                'source' => self::$domain,
                'phrase' => $phrase,
                'amount' => $amount,
                'last_update_date' => $this->getLastUpdateDate()
            ]);
        }
    }

}
