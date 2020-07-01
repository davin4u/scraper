<?php

namespace App\Crawler\Plugins;

use App\Crawler\Interfaces\CrawlerPluginInterface;

/**
 * Class RemoveJavascript
 * @package App\Crawler\Plugins
 */
class RemoveJavascript implements CrawlerPluginInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function handle(string $content): string
    {
        return preg_replace('/\<script(.+)\<\/script\>/siU', '', $content);
    }
}