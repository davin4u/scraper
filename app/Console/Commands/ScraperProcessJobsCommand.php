<?php

namespace App\Console\Commands;

use App\Exceptions\ScraperNotFoundException;
use App\ScraperJob;
use App\Scrapers\ScraperFactory;
use App\Scrapers\ScraperInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScraperProcessJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:process-jobs {--take=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scraping jobs';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $limit = $this->option('take') ?: 5;

        $jobs = ScraperJob::query()->whereNull('completed_at')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($jobs->count() === 0) {
            return;
        }

        foreach ($jobs as $job) {
            try {
                $this->handleUrlScraping($job->url);

                $job->update(['completed_at' => Carbon::now()->toDateTimeString()]);
            }
            catch (\Exception $e) {}

            sleep(7);
        }
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
}
