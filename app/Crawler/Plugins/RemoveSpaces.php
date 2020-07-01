<?php

namespace App\Crawler\Plugins;

use App\Crawler\Interfaces\CrawlerPluginInterface;

/**
 * Class RemoveSpaces
 * @package App\Crawler\Plugins
 */
class RemoveSpaces implements CrawlerPluginInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function handle(string $content): string
    {
        $content = str_replace("\n", "", $content);
        $content = preg_replace('/\s\s+/', ' ', $content);

        return $content;
    }
}