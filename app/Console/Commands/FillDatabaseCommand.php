<?php

namespace App\Console\Commands;

use App\Crawler\DatabaseFillers\NotikDatabaseFiller;
use Illuminate\Console\Command;

class FillDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize database';

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
        (new NotikDatabaseFiller($this->getOutput()))->handle();
    }
}
