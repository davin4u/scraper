<?php

namespace App\Crawler\Interfaces;

/**
 * Interface CrawlerPluginInterface
 * @package App\Crawler\Interfaces
 */
interface CrawlerPluginInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function handle(string $content) : string;
}