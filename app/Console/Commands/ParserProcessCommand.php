<?php

namespace App\Console\Commands;

use App\Exceptions\DocumentNotReadableException;
use App\Exceptions\DomainNotFoundException;
use App\Exceptions\ParserNotFoundException;
use App\Crawler\Document;
use App\Crawler\DocumentsRepository;
use App\Parsers\ParserFactory;
use App\Parsers\ParserInterface;
use App\Repositories\ProductsRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ParserProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:process {--domain=} {--date=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scraped files.';

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
        $date = $this->option('date') ?: ($this->option('all') ? date('d.m.Y') : null);

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

            $domain = $document->getDocumentDomain();

            try {
                /** @var ParserInterface $parser */
                $parser = (new ParserFactory)->get($document);

                $results = $parser->handle();

                if (!empty($results)) {
                    if ($parser->isSinglePageParser()) {
                        (new ProductsRepository())->domain($domain)->createOrUpdate($results);
                    }
                    else {
                        (new ProductsRepository())->domain($domain)->bulkCreateOrUpdate($results);
                    }
                }
            }
            catch (ParserNotFoundException $e) {
                Log::error("Parser NOT FOUND: " . $document->getPath());

                throw new \Exception("Parser not found.");
            }
            catch (DomainNotFoundException $e) {
                Log::error($e->getMessage());
            }
            catch (DocumentNotReadableException $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
