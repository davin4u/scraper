<?php

namespace App\Parsers;

use App\ClassesFactory;
use App\Exceptions\ParserNotFoundException;

class ParserFactory extends ClassesFactory
{
    /**
     * @var string
     */
    protected static $directory = __DIR__ . DIRECTORY_SEPARATOR . 'StoreParsers';

    /**
     * @param string $domain
     * @return mixed
     * @throws ParserNotFoundException
     */
    public static function get(string $domain)
    {
        foreach (static::$classes as $parser) {
            /** @var BaseParser $parser */

            if (class_exists($parser) && $parser::canHandle($domain)) {
                return new $parser;
            }
        }

        throw new ParserNotFoundException("There is no parser for given domain.");
    }
}
