<?php

namespace App\Parsers;

use App\Exceptions\DocumentNotFoundException;

class Document
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * Document constructor.
     * @param string $path
     * @throws DocumentNotFoundException
     */
    public function __construct(string $path)
    {
        $this->file = new \SplFileInfo(storage_path($path));

        if (! $this->file->isFile()) {
            throw new DocumentNotFoundException("Document {$this->file->getPath()} does not exist");
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $content = '';

        $o = $this->file->openFile();

        while (!$o->eof()) {
            $content .= $o->fread(1000);
        }

        return $content;
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
