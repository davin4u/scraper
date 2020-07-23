<?php

namespace App\Crawler\DatabaseFillers;

use App\ScraperJob;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

/**
 * Class NotikDatabaseFiller
 * @package App\Crawler\DatabaseFillers
 */
final class NotikDatabaseFiller
{
    /**
     * @var array
     */
    private $categories = [
        'https://www.notik.ru/search_catalog/filter/brand.htm',
        'https://www.notik.ru/search_catalog/filter/monoblocks.htm',
        'https://www.notik.ru/search_catalog/filter/allmonitors.htm',
        'https://www.notik.ru/search_catalog/filter/allpads.htm',
        'https://www.notik.ru/search_catalog/filter/allsmarts.htm'
    ];

    /**
     * @var array
     */
    private $jobs = [];

    /**
     * @var OutputStyle
     */
    private $output;

    /**
     * NotikDatabaseFiller constructor.
     * @param OutputStyle $output
     */
    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->output->text("Process categories...");

        $bar = $this->output->createProgressBar(count($this->categories));

        $bar->start();

        foreach ($this->categories as $url) {
            $this->checkCategory($url);

            $bar->advance();
        }

        $bar->finish();

        $this->output->success("Scraper jobs have been created.");

        if (count($this->jobs) > 0) {
            $this->output->text("Start scraping...");

            $bar = $this->output->createProgressBar(ceil(count($this->jobs) / 10));

            $bar->start();

            foreach (collect($this->jobs)->chunk(10) as $chunk) {
                /** @var Collection $chunk */

                Artisan::call('scraper:process-jobs', [
                    '--jobs' => $chunk->implode(',')
                ]);

                $bar->advance();
            }

            $bar->finish();

            $this->output->success("Content has been stored.");

            $this->output->text("Start parsing...");

            Artisan::call('parser:process', [
                '--date' => date('d.m.Y')
            ]);

            $this->output->success("Products have been processed.");
        }
    }

    private function wait()
    {
        sleep(rand(4, 10));
    }

    /**
     * @param string $url
     */
    private function createJob(string $url)
    {
        $job = ScraperJob::create([
            'url' => $url,
            'user_id' => 1
        ]);

        $this->jobs[] = $job->id;
    }

    /**
     * @param string $url
     */
    private function searchCategoryParser(string $url)
    {
        $this->wait();
        $html = file_get_contents($url);
        $urlsArray = [];
        $pagePattern = '/paginator(.+\s){1,7}<a.href=(.+)</u';

        preg_match($pagePattern, $html, $match);
        //In match[2] string like "'/search_catalog/filter/brand.htm?page=27' class='firstlast'>27"
        //So we split it and take second value
        $lastPage = (int)explode('>', $match[2])[1];

        //pattern works fine for notebooks, monoblocks and smartphones
        $urlPattern = '/<div><b><a.href="(.+).htm">/iu';

        preg_match_all($urlPattern, $html, $matches);
        array_walk($matches[1], function (&$value) {
            $value = "https://www.notik.ru{$value}";
        });
        array_push($urlsArray, $matches[1]);

        //get links from other pages and add to them a prefix
        for ($i = 2; $i <= $lastPage; $i++) {
            //pagination starts from 2nd page
            $this->wait();
            $html = file_get_contents($url . '?page=' . $i);
            $urlPattern = '/<div><b><a.href="(.+).htm">/iu';

            preg_match_all($urlPattern, $html, $matches);
            array_walk($matches[1], function (&$value) {
                $value = "https://www.notik.ru{$value}";
            });
            array_push($urlsArray, $matches[1]);
        }
        //insert arrays in database
        for ($i = 0; $i < $lastPage; $i++) {
            foreach ($urlsArray[$i] as $url) {
                $this->createJob($url);
            }
        }
    }

    /**
     * @param string $url
     */
    private function padsParser(string $url)
    {
        $this->wait();
        $html = file_get_contents($url);
        $urlsArray = [];
        $urlPattern = '/href="\/goods(.+).htm"/iu';

        preg_match_all($urlPattern, $html, $matches);
        $matches[1] = array_unique($matches[1]); //some fields can repeated 3 times
        array_walk($matches[1], function (&$value) {
            $value = "https://www.notik.ru/goods{$value}";
        });
        array_push($urlsArray, $matches[1]);

        $pagePattern = '/paginator(.+\s){1,7}<a.href=(.+)</u';
        preg_match($pagePattern, $html, $match);
        $lastPage = (int)explode('>', $match[2])[1];

        for ($i = 2; $i <= $lastPage; $i++) {
            $this->wait();
            $html = file_get_contents($url . '?page=' . $i);

            preg_match_all($urlPattern, $html, $matches);
            $matches[1] = array_unique($matches[1]);
            array_walk($matches[1], function (&$value) {
                $value = "https://www.notik.ru/goods{$value}";
            });
            array_push($urlsArray, $matches[1]);
        }

        for ($i = 0; $i < $lastPage; $i++) {
            foreach ($urlsArray[$i] as $url) {
                $this->createJob($url);
            }
        }
    }

    /**
     * @param string $url
     */
    private function monitorsParser(string $url)
    {
        $this->wait();
        $html = file_get_contents($url);
        $urlsArray = [];

        //[*]тут логика парсинга ссылок с одной страницы
        $urlPattern = '/href="\/goods(.+).htm"/iu';

        preg_match_all($urlPattern, $html, $matches);

        //чтобы не пихать в urlsArray сразу, там потом будет массив массивов, а иначе пишется массив в последний элемент
        //Поэтому временный массив
        $tempArray = [];
        foreach ($matches[1] as $match) {
            array_push($tempArray, explode(' ', $match)[0]);
        }
        $tempArray = array_splice($tempArray, 1); //в начале лишняя ссылка
        array_walk($tempArray, function (&$value) {
            $value = "https://www.notik.ru/goods{$value}";
        });

        //remove " sign from urls
        $tempArray = preg_replace('/"/', '', $tempArray);
        array_push($urlsArray, $tempArray);
        //[*]конец

        $pagePattern = '/paginator(.+\s){1,7}<a.href=(.+)</u';
        preg_match($pagePattern, $html, $match);
        $lastPage = (int)explode('>', $match[2])[1];

        for ($i = 2; $i <= $lastPage; $i++) {
            $this->wait();
            $html = file_get_contents($url . '?page=' . $i);
            $urlPattern = '/href="\/goods(.+).htm"/iu';

            preg_match_all($urlPattern, $html, $matches);
            $tempArray = [];
            foreach ($matches[1] as $match) {
                array_push($tempArray, explode(' ', $match)[0]);
            }
            $tempArray = array_splice($tempArray, 1);
            array_walk($tempArray, function (&$value) {
                $value = "https://www.notik.ru/goods{$value}";
            });

            $tempArray = preg_replace('/"/', '', $tempArray);
            array_push($urlsArray, $tempArray);
        }

        for ($i = 0; $i < $lastPage; $i++) {
            foreach ($urlsArray[$i] as $url) {
                $this->createJob($url);
            }
        }
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    private function checkCategory(string $url)
    {
        $slicedUrl = preg_split('/(\.|\/)/', $url);

        //searchCategory it is url like https://www.notik.ru/search_catalog/filter/brand.htm
        $searchCategory = ['filter'];

        $categoryName = array_slice($slicedUrl, -2)[0];
        $searchCategoryName = array_slice($slicedUrl, -3)[0];

        if (in_array($searchCategoryName, $searchCategory)) {
            switch ($categoryName) {
                case 'allpads':
                    $this->padsParser($url);
                    break;
                case 'allmonitors':
                    $this->monitorsParser($url);
                    break;
                default:
                    $this->searchCategoryParser($url);
            }
        }
        else {
            throw new \Exception("Category was not recognized.");
        }
    }
}