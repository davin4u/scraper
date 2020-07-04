<?php

namespace App\Parsers;

/**
 * Interface ParserInterface
 * @package App\Parsers
 */
interface ParserInterface
{
    /**
     * @return mixed
     */
    public function handle();

    /**
     * @param Document $document
     * @return bool
     */
    public static function canHandle(Document $document): bool;

    /**
     * @return bool
     */
    public function isSinglePageParser(): bool;
}
