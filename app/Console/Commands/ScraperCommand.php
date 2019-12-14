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
            $category = ScraperCategory::query()->orderBy('last_visiting_at')->first();

            $category->update([
                'last_visiting_at' => Carbon::now()->toDateTimeString()
            ]);

            $url = $category->url;
        }

        try {
            /** @var BaseScraper $scraper */
            $scraper = (new ScraperFactory())->get($url);

            $scraper->handle($url);
        }
        catch (ScraperNotFoundException $e) {
            Log::error("Scraper NOT FOUND: $url");

            throw new \Exception("Scraper not found.");
        }
    }
}
