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
     *
     * @var string
     */
    protected $signature = 'parser:process {--domain=} {--date=} {--collect-links}';

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

                if ($this->option('collect-links')) {
                    $parser->setOptions(['collect-links']);
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
