<?php

namespace App\Console\Commands;

use App\Exceptions\DocumentNotReadableException;
use App\Exceptions\DomainNotFoundException;
use App\Exceptions\ParserNotFoundException;
use App\Parsers\Document;
use App\Parsers\DocumentsRepository;
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
    protected $signature = 'parser:process {--domain=} {--date=}';

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
        if ($this->option('domain') && $this->option('date')) {
            $this->files->domain($this->option('domain'));

            $this->files->date(Carbon::parse($this->option('date')));

            $this->parseDocuments($this->files->get());
        }
        else {
            $directories = array_map(function ($item) {
                return explode('/', $item)[1];
            }, Storage::disk('local')->directories('scraper'));

            if (!empty($directories)) {
                foreach ($directories as $domain) {
                    $this->files->domain($domain);

                    $this->files->date(Carbon::now());

                    $this->parseDocuments($this->files->get());
                }
            }
        }
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

                $results = $parser->handle($document->getContent(false));

                if (!empty($results)) {
                    if ($parser->isSinglePageParser()) {
                        (new ProductsRepository())->domain($domain)->createOrUpdate($results);
                    }
                    else {
                        (new ProductsRepository())->domain($domain)->bulkCreateOrUpdate($results);
                    }
                }

                $document->unlock();
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
