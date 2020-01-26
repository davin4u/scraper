<?php

namespace App\Console\Commands;

use App\Matcher\ProductsMatcher;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class MatcherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matcher:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find possible matches for products';

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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        /** @var ProductsMatcher $matcher */
        $matcher = app()->make(ProductsMatcher::class);

        $total   = Product::query()->count();
        $bar     = $this->output->createProgressBar($total);

        $bar->start();

        Product::query()->chunk(200, function (Collection $products) use ($bar, $matcher) {
            foreach ($products as $product) {
                $matches = $matcher->findPossibleMatches($product);

                if (!empty($matches)) {
                    $matcher->logMatches($product, $matches);
                }

                $bar->advance();
            }
        });

        $bar->finish();
    }
}
