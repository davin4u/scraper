<?php

namespace App\MediaUploader\Handlers;

use App\MediaUploader\Interfaces\MediaStorageInterface;
use App\MediaUploader\Interfaces\UploaderInterface;

class FromRequestUploader implements UploaderInterface
{
    /**
     * @var MediaStorageInterface
     */
    protected $storage;

    /**
     * FromRequestUploader constructor.
     * @param MediaStorageInterface $storage
     */
    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function save($fileOrUrl)
    {
        // TODO: Implement save() method.
    }
}