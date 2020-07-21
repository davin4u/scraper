<?php

namespace App\Http\Controllers;

use App\Crawler\Document;
use App\Crawler\DocumentsRepository;
use App\Http\Requests\PluginParsingRequest;
use App\Parsers\ParserFactory;
use App\Parsers\ParserInterface;
use Carbon\Carbon;

/**
 * Class ParserController
 * @package App\Http\Controllers
 */
class ParserController extends Controller
{
    /**
     * @var DocumentsRepository
     */
    protected $documents;

    /**
     * ParserController constructor.
     */
    public function __construct()
    {
        $this->documents = new DocumentsRepository();
    }

    /**
     * @param PluginParsingRequest $request
     * @throws \App\Exceptions\DocumentNotReadableException
     * @throws \App\Exceptions\ParserNotFoundException
     */
    public function process(PluginParsingRequest $request)
    {
        $url = $request->get('url');
        $domain = parse_url($url, PHP_URL_HOST);
        $html = $request->get('html');

        /** @var Document $document */
        $document = $this->documents
            ->domain($domain)
            ->date(Carbon::now())
            ->fileName(md5($url) . '.html')
            ->put($html);

        if (!is_null($document)) {
            /** @var ParserInterface $parser */
            $parser = (new ParserFactory())->get($document);

            $parser->handle();
        }
    }
}
