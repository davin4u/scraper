<?php

namespace App\Traits;

use App\Media;
use App\MediaUploader\MediaUploader;
use Illuminate\Http\Request;

/**
 * Trait WithMedia
 * @package App\Traits
 */
trait WithMedia
{
    /**
     * @param string|array $url
     */
    public function saveFilesFromUrl($url)
    {
        if (is_string($url)) {
            $url = [$url];
        }

        if (is_array($url)) {
            MediaUploader::getUrlUploader()->save($url);
        }

        throw new \InvalidArgumentException("url must be either string or array of strings");
    }

    /**
     * @param Request $request
     * @param string $requestPropertyName
     */
    public function saveFilesFromRequest(Request $request, $requestPropertyName = 'files')
    {

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function media()
    {
        $intermediate_table = strtolower(collect(explode('\\', static::class))->last()) . '_media';

        return $this->belongsToMany(Media::class, $intermediate_table);
    }
}