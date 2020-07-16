<?php

namespace App\Crawler\Matchers;

use App\Crawler\Exceptions\CrawlerValidationException;
use App\Crawler\Interfaces\Matchable;

/**
 * Class SimpleAttributeMatcher
 * @package App\Crawler\Matchers
 */
class SimpleAttributeMatcher extends SimpleMatcher implements Matchable
{
    /**
     * @var string
     */
    protected $model = \App\Attribute::class;

    /**
     * @param string $name
     * @param array $props
     * @return array
     * @throws CrawlerValidationException
     */
    protected function getCreateData(string $name, array $props = []): array
    {
        if (empty($props['category_id'])) {
            throw new CrawlerValidationException("category_id is required");
        }

        return [
            'name' => $name,
            'map' => [$name],
            'category_id' => $props['category_id']
        ];
    }
}