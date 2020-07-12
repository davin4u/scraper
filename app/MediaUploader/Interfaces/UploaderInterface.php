<?php

namespace App\MediaUploader\Interfaces;

interface UploaderInterface
{
    public function save($fileOrUrl);
}