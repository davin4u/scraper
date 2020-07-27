<?php

namespace App\Observers;

use App\Media;
use App\MediaUploader\MediaStorage;

class MediaObserver
{
    /**
     * Handle the media "deleted" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        (new MediaStorage())->delete($media->path);
    }
}
