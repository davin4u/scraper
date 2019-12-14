<?php

namespace App\Parsers\StoreParsers\DnsShopParsers;

use App\Parsers\BaseParser;

class DnsShopCategoryParser extends BaseParser
{
    /**
     * @var string
     */
    protected static $domain = 'dns-shop.ru';

    /**
     * @param $content
     * @return mixed
     */
    public function handle($content)
    {
        return 'hey, i am dns shop category parser';
    }
}
