<?php

namespace App\MediaUploader;

use App\MediaUploader\Interfaces\MediaStorageInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class MediaStorage
 * @package App\MediaUploader
 */
class MediaStorage implements MediaStorageInterface
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $storage;

    /**
     * MediaStorage constructor.
     */
    public function __construct()
    {
        $this->storage = Storage::disk(config('media.storage_disk'));
    }

    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    public function put(string $path, string $content): bool
    {
        return $this->storage->put($path, $content);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if ($this->storage->exists($path)) {
            return $this->storage->delete($path);
        }

        throw new FileNotFoundException($path);
    }
}