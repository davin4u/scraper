<?php

namespace App\Crawler\Plugins;

use App\Crawler\Interfaces\CrawlerPluginInterface;

/**
 * Class RemoveCss
 * @package App\Crawler\Plugins
 */
class RemoveCss implements CrawlerPluginInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function handle(string $content): string
    {
        return preg_replace('/\<style(.+)\<\/style\>/siU', '', $content);
    }
}