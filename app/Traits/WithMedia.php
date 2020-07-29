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
     * @return bool
     */
    public function saveFilesFromUrl($url)
    {
        if (is_string($url)) {
            $url = [$url];
        }

        if (is_array($url)) {
            $files = MediaUploader::getUrlUploader()->save($url, $this->getSaveToPath());

            $this->createMedia($files);

            return true;
        }

        throw new \InvalidArgumentException("url must be either string or array of strings");
    }

    /**
     * @param Request $request
     * @param string $requestPropertyName
     * @return bool
     */
    public function saveFilesFromRequest(Request $request, $requestPropertyName = 'files')
    {
        $allFiles = $request->allFiles();

        if (!empty($allFiles) && isset($allFiles[$requestPropertyName])) {
            $files = MediaUploader::getFilesUploader()->save($allFiles[$requestPropertyName], $this->getSaveToPath());

            $this->createMedia($files);

            return true;
        }

        throw new \InvalidArgumentException("No files to save given.");
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
     * @param int $mediaId
     * @throws \Exception
     */
    public function deleteFile(int $mediaId)
    {
        $media = $this->media()->find($mediaId);

        $this->media()->detach([$mediaId]);

        $media->delete();
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

    private function createMedia(array $files)
    {
        if (!empty($files)) {
            $productMedia = [];

            foreach ($files as $file) {
                if ($media = Media::create($file)) {
                    $productMedia[] = $media->id;
                }
            }

            if (!empty($productMedia)) {
                $this->media()->syncWithoutDetaching($productMedia);
            }
        }
    }
}
