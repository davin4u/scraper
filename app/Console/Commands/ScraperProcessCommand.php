<?php

namespace App\Console\Commands;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingTerminatedException;
use App\Exceptions\WebdriverPageNotReachableException;
use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperCategory;
use App\Scrapers\ScraperFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScraperProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:process {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scraping tasks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $url = $this->option('url');

        if (!$url) {
            $category = ScraperCategory::query()->orderBy('scraping_started_at')->first();

            $initialStartedDate = $category->scraping_started_at;

            $category->update([
                'scraping_started_at' => Carbon::now()->toDateTimeString()
            ]);

            $url = $category->url;
        }

        try {
            /** @var BaseScraper $scraper */
            $scraper = (new ScraperFactory())->get($url);

            $scraper->handle($url);

            if (isset($category)) {
                $category->update([
                    'scraping_finished_at' => Carbon::now()->toDateTimeString()
                ]);
            }
        }
        catch (ScraperNotFoundException $e) {
            Log::error("[SCRAPER] Scraper for $url was not found.");

            throw new \Exception("Scraper not found.");
        }
        catch (ScrapingTerminatedException $e) {
            if (isset($category) && isset($initialStartedDate)) {
                $category->update([
                    'scraping_started_at' => $initialStartedDate
                ]);
            }
        }
    }
}
