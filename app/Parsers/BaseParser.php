<?php

namespace App\Parsers;

/**
 * Class BaseParser
 * @package App\Parsers
 */
abstract class BaseParser
{
    /**
     * @var string
     */
    protected static $domain = '';

    /**
     * @param $content
     * @return mixed
     */
    abstract public function handle($content);

    /**
     * @param $domain
     * @return bool
     */
    public static function canHandle($domain)
    {
        return strpos(static::$domain, $domain) !== false;
    }
}
