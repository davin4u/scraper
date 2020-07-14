<?php

namespace App\MediaUploader\Interfaces;

/**
 * Interface UploaderInterface
 * @package App\MediaUploader\Interfaces
 */
interface UploaderInterface
{
    /**
     * @param $fileOrUrl
     * @param string $folder
     * @return array
     */
    public function save($fileOrUrl, string $folder);
}