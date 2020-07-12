<?php

namespace App\MediaUploader\Handlers;

use App\MediaUploader\Interfaces\UploaderInterface;
use Illuminate\Support\Facades\Storage;

class FromUrlUploader implements UploaderInterface
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * FromUrlUploader constructor.
     */
    public function __construct()
    {
        $this->storage = Storage::disk(config('filesystems.media_storage'));
    }

    public function save($fileOrUrls)
    {
        // TODO: Implement save() method.
    }
}