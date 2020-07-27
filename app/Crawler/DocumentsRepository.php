<?php

namespace App\Crawler;

use App\Exceptions\DocumentNotFoundException;
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
     * @var string
     */
    protected $fileName = null;

    /**
     * @var int
     */
    protected $limit = null;

    /**
     * @var string
     */
    private $directory = 'scraper';

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
     * @param string $fileName
     * @return $this
     */
    public function fileName(string $fileName)
    {
        $this->fileName = $fileName;

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
        $files = array_values(array_filter($this->storage->files($this->directory, true), function ($file) {
            return strpos($file, '.html') !== false;
        }));

        if (!is_null($this->date)) {
            $date = $this->date->format('d.m.Y');

            $files = array_filter($files, function ($file) use ($date) {
                return strpos($file, $date) !== false;
            });
        }

        if (!is_null($this->domain)) {
            $domain = $this->domain;

            $files = array_filter($files, function ($file) use ($domain) {
                return strpos($file, $domain) !== false;
            });
        }

        if (!is_null($this->limit)) {
            $files = array_slice($files, 0, $this->limit);
        }

        $files = array_map(function ($path) {
            return new Document($this->documentPath($path));
        }, $files);

        return array_values($files);
    }

    /**
     * @param string $content
     * @return Document|null
     */
    public function put(string $content)
    {
        if ($this->storage->put($this->buildStoragePath(), $content)) {
            try {
                return new Document($this->documentPath($this->buildStoragePath()));
            }
            catch (DocumentNotFoundException $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * @param $path
     * @return string
     */
    private function documentPath($path)
    {
        return 'app/' . $path;
    }

    private function buildStoragePath(): string
    {
        $path = $this->directory;

        if (!is_null($this->date)) {
            $path .= DIRECTORY_SEPARATOR . $this->date->format('d.m.Y');
        }

        if (!is_null($this->domain)) {
            $path .= DIRECTORY_SEPARATOR . $this->domain;
        }

        if (!is_null($this->fileName)) {
            $path .= DIRECTORY_SEPARATOR . $this->fileName;
        }

        return $path;
    }
}
