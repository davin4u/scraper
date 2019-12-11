<?php

namespace App\Console\Commands;

use App\Exceptions\ScraperNotFoundException;
use App\Scrapers\BaseScraper;
use App\Scrapers\ScraperCategory;
use App\Scrapers\ScraperFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:process';

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
        $category = ScraperCategory::query()->orderBy('last_visiting_at')->first();

        $category->update([
            'last_visiting_at' => Carbon::now()->toDateTimeString()
        ]);

        try {
            /** @var BaseScraper $scraper */
            $scraper = ScraperFactory::get($category->url);

            $scraper->handle($category->url);
        }
        catch (ScraperNotFoundException $e) {
            Log::error("Scraper NOT FOUND: $category->url");

            throw new \Exception("Scraper not found.");
        }
    }
}
