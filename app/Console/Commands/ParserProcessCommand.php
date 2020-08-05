<?php

namespace App\Console\Commands;

use App\Exceptions\DocumentNotReadableException;
use App\Exceptions\ParserNotFoundException;
use App\Crawler\Document;
use App\Crawler\DocumentsRepository;
use App\Exceptions\ProductNotFoundException;
use App\Parsers\ParserFactory;
use App\Parsers\ParserInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ParserProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     * --domain - process only html documents of given domain
     *
     * --date - process only html documents for given date
     *
     * --collect-links - is used for category scraping,
     *   if set it creates scraping jobs for category products,
     *   if not, products data will be collected and products will be created
     *
     * --init - we use it to determine if that is "initial" parsing
     *   if so, for processed documents we create entities in our products database
     *   if it is not set, we create/update StoreProducts
     *
     * @var string
     */
    protected $signature = 'parser:process {--domain=} {--date=} {--collect-links} {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process stored files.';

    /**
     * @var DocumentsRepository
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->files = new DocumentsRepository();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $date = $this->option('date') ?: null;

        if ($date) {
            try {
                $this->files->date(Carbon::parse($date));
            }
            catch (\Exception $e) {
                $this->error('Wrong date format');
            }
        }

        if ($domain = $this->option('domain')) {
            $this->files->domain($domain);
        }

        $this->parseDocuments($this->files->get());
    }

    private function parseDocuments($documents)
    {
        if (empty($documents)) {
            return;
        }

        foreach ($documents as $document) {
            /** @var Document $document */

            try {
                /** @var ParserInterface $parser */
                $parser = (new ParserFactory)->get($document);

                $options = [];

                if ($this->option('collect-links')) {
                    $options[] = 'collect-links';
                }

                if ($this->option('init')) {
                    $options[] = 'init';
                }

                if (!empty($options)) {
                    $parser->setOptions($options);
                }

                $parser->handle();
            }
            catch (ParserNotFoundException $e) {
                Log::error("Parser NOT FOUND: " . $document->getPath());

                throw new \Exception("Parser not found.");
            }
            catch (ProductNotFoundException $e) {
                Log::error($e->getMessage());
            }
            catch (DocumentNotReadableException $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
