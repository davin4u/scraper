<?php

namespace App\MediaUploader\Handlers;

use App\MediaUploader\Interfaces\UploaderInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;
use Illuminate\Support\Facades\Storage;

/**
 * Class FromUrlUploader
 * @package App\MediaUploader\Handlers
 */
class FromUrlUploader implements UploaderInterface
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * FromUrlUploader constructor.
     */
    public function __construct()
    {
        $this->storage = Storage::disk(config('media.storage_disk'));
    }

    /**
     * @param $fileOrUrls
     * @param string $folder
     * @return array|mixed
     */
    public function save($fileOrUrls, string $folder)
    {
        $files = [];

        foreach ($fileOrUrls as $url) {
            $filename = $this->getFileName($url);

            $path = $folder . DIRECTORY_SEPARATOR . $filename;

            if ($this->storage->put($path, file_get_contents($url))) {
                $file = new \SplFileObject(public_path('media/' . $path));

                $files[] = [
                    'filename'     => $filename,
                    'path'         => $path,
                    'full_path'    => $file->getRealPath(),
                    'size'         => $file->getSize(),
                    'url'          => $this->getUrl($path),
                    'type'         => mimetype_from_filename($filename),
                    'extension'    => $file->getExtension(),
                    'storage'      => config('filesystems.disks.' . config('media.storage_disk') . '.driver')
                ];
            }
        }

        return $files;
    }

    /**
     * @param string $url
     * @return string
     */
    protected function getFileName(string $url): string
    {
        $parts = explode('.', $url);

        return md5($url) . "." . end($parts);
    }

    /**
     * @param $path
     * @return string
     */
    protected function getUrl($path): string
    {
        $path = implode('/', explode(DIRECTORY_SEPARATOR, $path));

        return config('media.app_url') . '/media/' . $path;
    }
}