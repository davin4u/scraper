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
            MediaUploader::getUrlUploader()->save($url, $this->getSaveToPath());
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
        $intermediate_table = $this->getModelName() . '_media';

        return $this->belongsToMany(Media::class, $intermediate_table);
    }

    /**
     * @return string
     */
    protected function getSaveToPath(): string
    {
        $path = $this->getModelName();

        if ($folder = $this->getMediaFolder()) {
            $path .= DIRECTORY_SEPARATOR . $folder;
        }

        return $path;
    }

    /**
     * @return string
     */
    protected function getMediaFolder(): string
    {
        return null;
    }

    /**
     * @return string
     */
    private function getModelName()
    {
        return strtolower(collect(explode('\\', static::class))->last());
    }
}