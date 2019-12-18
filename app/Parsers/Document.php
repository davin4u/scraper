<?php

namespace App\Parsers;

use App\Exceptions\DocumentNotFoundException;
use App\Exceptions\DocumentNotReadableException;

class Document
{
    /**
     * @var \SplFileObject
     */
    protected $file;

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
    }

    /**
     * @param bool $unlock
     * @return string
     * @throws DocumentNotReadableException
     */
    public function getContent($unlock = true)
    {
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

        return $path[count($path) - 2];
    }
}
