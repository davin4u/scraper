<?php

namespace App\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DocumentsRepository
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * @var string
     */
    protected $domain = null;

    /**
     * @var Carbon
     */
    protected $date = null;

    /**
     * @var int
     */
    protected $limit = null;

    /**
     * @var string
     */
    private $directory = '';

    /**
     * DocumentsRepository constructor.
     */
    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function domain(string $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function date(Carbon $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function take(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        $this->buildDirectory();

        $files = array_values(array_filter($this->storage->files($this->directory, true), function ($file) {
            return strpos($file, '.html') !== false;
        }));

        $files = array_map(function ($path) {
            return new Document($this->documentPath($path));
        }, $files);

        if (!is_null($this->date)) {
            $files = array_filter($files, function ($file) {
                /** @var Document $file */

                return $file->getCreatedAt()->gt($this->date->startOfDay()) && $file->getCreatedAt()->lt($this->date->endOfDay());
            });
        }

        if (!is_null($this->limit)) {
            $files = array_slice($files, 0, $this->limit);
        }

        return array_values($files);
    }

    private function buildDirectory()
    {
        $dir = 'scraper';

        if (!is_null($this->domain)) {
            $dir .= DIRECTORY_SEPARATOR . $this->domain;
        }

        $this->directory = $dir;
    }

    /**
     * @param $path
     * @return string
     */
    private function documentPath($path)
    {
        return 'app/' . $path;
    }
}
