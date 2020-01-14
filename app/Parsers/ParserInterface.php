<?php

namespace App\Parsers;

/**
 * Interface ParserInterface
 * @package App\Parsers
 */
interface ParserInterface
{
    /**
     * @param string $content
     * @return mixed
     */
    public function handle(string $content);

    /**
     * @param Document $document
     * @return bool
     */
    public static function canHandle(Document $document) : bool;

    /**
     * @return bool
     */
    public function isSinglePageParser() : bool;
}
