<?php

namespace App;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

/**
 * Class ClassesFactory
 * @package App
 */
class ClassesFactory
{
    /**
     * @var null
     */
    protected static $directory = null;

    /**
     * @var array
     */
    protected static $classes = [];

    /**
     * ClassesFactory constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        /**
         * @TODO find the way how to keep previously loaded classes without need to load them on each execution
         * @TODO issue when both ParserFactory and ScrapersFactory are called on single request
         */
        static::loadClasses();
    }

    protected static function loadClasses($directory = null)
    {
        if (is_null(static::$directory)) {
            throw new \Exception('Property `directory` is not set in ' . __CLASS__);
        }

        $directory = $directory ?: static::$directory;

        $files = (new Finder())->in([$directory])->files();

        foreach ($files as $file) {
            static::$classes[] = 'App\\' . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($file->getPathname(), realpath(app_path()).DIRECTORY_SEPARATOR)
            );
        }
    }
}
