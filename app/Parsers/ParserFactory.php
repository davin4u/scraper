<?php

namespace App\Parsers;

use App\ClassesFactory;
use App\Crawler\Document;
use App\Exceptions\ParserNotFoundException;

class ParserFactory extends ClassesFactory
{
    /**
     * @var string
     */
    protected static $directory = __DIR__ . DIRECTORY_SEPARATOR . 'StoreParsers';

    /**
     * @param Document $document
     * @return ParserInterface
     * @throws ParserNotFoundException
     * @throws \App\Exceptions\DocumentNotReadableException
     */
    public static function get(Document $document) : ParserInterface
    {
        foreach (static::$classes as $parser) {
            /** @var ParserInterface $parser */

            if (class_exists($parser) && $parser::canHandle($document)) {
                return new $parser($document->getContent());
            }
        }

        throw new ParserNotFoundException("There is no parser for given domain.");
    }
}
