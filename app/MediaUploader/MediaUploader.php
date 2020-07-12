<?php

namespace App\MediaUploader;

use App\MediaUploader\Handlers\FromRequestUploader;
use App\MediaUploader\Handlers\FromUrlUploader;
use App\MediaUploader\Interfaces\UploaderInterface;

class MediaUploader
{
    /**
     * @return UploaderInterface
     */
    public static function getUrlUploader(): UploaderInterface
    {
        return new FromUrlUploader();
    }

    /**
     * @return UploaderInterface
     */
    public static function getFilesUploader(): UploaderInterface
    {
        return new FromRequestUploader();
    }
}