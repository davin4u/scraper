<?php

namespace App\Console\Commands;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingTerminatedException;
use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperCategory;
use App\Scrapers\ScraperFactory;
use App\Scrapers\ScraperInterface;
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
    protected $signature = 'scraper:process {--url=} {--products} {--take=}';

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
        if ($this->option('products')) {
            $this->handleProductsScraping($this->option('take') ? (int)$this->option('take') : 100);

            return;
        }

        $url = $this->option('url');

        if (!$url) {
            $category = ScraperCategory::query()->orderBy('scraping_started_at')->first();

            if (!$category) {
                return;
            }

            $initialStartedDate = $category->scraping_started_at;

            $category->update([
                'scraping_started_at' => Carbon::now()->toDateTimeString()
            ]);

            $url = $category->url;
        }

        try {
            /** @var ScraperInterface $scraper */
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

    public function handleProductsScraping($take = 100)
    {
        $products = \App\Product::query()->orderBy('scraped_at')->orderBy('id')->take($take)->get();

        foreach ($products as $product) {
            try {
                /** @var ScraperInterface $scraper */
                $scraper = (new ScraperFactory())->get($product->url);

                $scraper->handle($product->url);

                $product->update([
                    'scraped_at' => Carbon::now()->toDateTimeString()
                ]);
            }
            catch (ScraperNotFoundException $e) {
                Log::error("[SCRAPER] Scraper for $product->url was not found.");

                throw new \Exception("Scraper not found.");
            }
            catch (ScrapingTerminatedException $e) {

            }
        }
    }
}
