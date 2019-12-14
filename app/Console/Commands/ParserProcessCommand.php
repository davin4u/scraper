<?php

namespace App\Console\Commands;

use App\Exceptions\ParserNotFoundException;
use App\Parsers\BaseParser;
use App\Parsers\Document;
use App\Parsers\DocumentsRepository;
use App\Parsers\ParserFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ParserProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:process {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scraped files.';

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
        if ($this->option('domain')) {
            $this->files->domain($this->option('domain'));
        }

        $documents = $this->files->get();

        if (empty($documents)) {
            return;
        }

        foreach ($documents as $document) {
            /** @var Document $document */

            $domain = $document->getDocumentDomain();

            try {
                /** @var BaseParser $parser */
                $parser = (new ParserFactory)->get($domain);

                $parser->handle($document->getContent());
            }
            catch (ParserNotFoundException $e) {
                Log::error("Parser NOT FOUND: $domain");

                throw new \Exception("Parser not found.");
            }
        }
    }
}
