<?php

namespace App\Parsers;

use App\Crawler\Document;

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
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);
}
