<?php

namespace App\Matcher;

use App\ClassesFactory;

/**
 * Class PluginsFactory
 * @package App\Matcher
 */
class PluginsFactory extends ClassesFactory
{
    /**
     * @var string
     */
    protected static $directory = __DIR__ . DIRECTORY_SEPARATOR . 'ProductMatcherPlugins';

    /**
     * @return array
     */
    public function getPlugins()
    {
        $plugins = [];

        foreach (static::$classes as $class) {
            /** @var MatcherPluginInterface $class */

            if (class_exists($class)) {
                $plugins[] = $class::getInstance();
            }
        }

        return $plugins;
    }
}
