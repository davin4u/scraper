<?php

namespace App\MediaUploader\Interfaces;

/**
 * Interface MediaStorageInterface
 * @package App\MediaUploader\Interfaces
 */
interface MediaStorageInterface
{
    /**
     * @param string $path
     * @param string $content
     * @return bool
     */
    public function put(string $path, string $content): bool;

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;
}