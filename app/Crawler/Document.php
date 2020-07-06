<?php

namespace App\Crawler;

use App\Exceptions\DocumentNotFoundException;
use App\Exceptions\DocumentNotReadableException;
use App\Exceptions\MethodNotFoundException;
use Illuminate\Support\Carbon;

class Document
{
    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * @var Carbon
     */
    protected $created_at;

    /** @var string */
    protected $content;

    /**
     * Document constructor.
     * @param string $path
     * @throws DocumentNotFoundException
     */
    public function __construct(string $path)
    {
        $this->file = new \SplFileObject(storage_path($path));

        if (! $this->file->isFile()) {
            throw new DocumentNotFoundException("Document {$this->file->getPath()} does not exist");
        }

        $this->created_at = Carbon::createFromTimestamp($this->file->getMTime());
    }

    /**
     * @param bool $unlock
     * @return string
     * @throws DocumentNotReadableException
     */
    public function getContent($unlock = true)
    {
        if (!empty($this->content)) {
            return $this->content;
        }

        if (!$this->file->isReadable()) {
            throw new DocumentNotReadableException("Document {$this->file->getPath()} seems to be locked.");
        }

        $this->file->flock(LOCK_EX);

        $content = '';

        while (!$this->file->eof()) {
            $content .= $this->file->fread(1000);
        }

        if ($unlock) {
            $this->unlock();
        }

        $this->content = $content;

        return $content;
    }

    public function unlock()
    {
        $this->file->flock(LOCK_UN);
    }

    /**
     * @return mixed
     */
    public function getDocumentDomain()
    {
        $path = explode('/', $this->file->getPath());

        return $path[count($path) - 1];
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->file->getPath() . DIRECTORY_SEPARATOR . $this->file->getFilename();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->file, $name)) {
            throw new MethodNotFoundException("Method '$name' does not exists.");
        }

        return !empty($arguments) ? $this->file->{$name}(...$arguments) : $this->file->{$name}();
    }
}
