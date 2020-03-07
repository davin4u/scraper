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
use Illuminate\Database\Eloquent\Collection;
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
        // scraping using products url
        if ($this->option('products')) {
            $this->handleProductsScraping($this->option('take') ? (int)$this->option('take') : 100);

            return;
        }

        $url = $this->option('url');

        // scraping using scraping categories
        if (!$url) {
            /** @var Collection $categories */
            $categories = ScraperCategory::query()
                ->whereRaw('(`scraping_finished_at` IS NULL OR `scraping_finished_at` < ?)', [Carbon::now()->subDay()->endOfDay()->toDateTimeString()])
                ->orderBy('scraping_started_at')
                ->limit(5)
                ->get();

            if ($categories->count() === 0) {
                return;
            }

            $initialTime = [];
            foreach ($categories as $category) {
                $initialTime[$category->id] = $category->scraping_started_at;
            }

            ScraperCategory::whereIn('id', $categories->pluck('id')->toArray())
                ->update([
                    'scraping_started_at' => Carbon::now()->toDateTimeString()
                ]);

            foreach ($categories as $category) {
                try {
                    if ($this->handleUrlScraping($category->url)) {
                        unset($initialTime[$category->id]);

                        $category->update([
                            'scraping_finished_at' => Carbon::now()->toDateTimeString()
                        ]);
                    }
                }
                catch (ScrapingTerminatedException $e) {
                    if (isset($category) && isset($initialTime[$category->id])) {
                        $category->update([
                            'scraping_started_at' => $initialTime[$category->id]
                        ]);
                    }
                }

                sleep(7);
            }

            return;
        }

        // scraping by url
        $this->handleUrlScraping($url);
    }

    /**
     * @param string $url
     * @return bool
     * @throws \Exception
     */
    protected function handleUrlScraping(string $url)
    {
        try {
            /** @var ScraperInterface $scraper */
            $scraper = (new ScraperFactory())->get($url);

            $scraper->handle($url);

            return true;
        }
        catch (ScraperNotFoundException $e) {
            Log::error("[SCRAPER] Scraper for $url was not found.");

            throw new \Exception("Scraper not found.");
        }
    }

    /**
     * @param int $take
     * @throws \Exception
     */
    protected function handleProductsScraping($take = 100)
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
