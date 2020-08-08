<?php

namespace App\MediaUploader\Handlers;

use App\MediaUploader\Interfaces\MediaStorageInterface;
use App\MediaUploader\Interfaces\UploaderInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;

/**
 * Class FromUrlUploader
 * @package App\MediaUploader\Handlers
 */
class FromUrlUploader implements UploaderInterface
{
    /**
     * @var MediaStorageInterface
     */
    protected $storage;

    /**
     * FromUrlUploader constructor.
     * @param MediaStorageInterface $storage
     */
    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
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

            $path = $this->buildPath($folder, $filename);

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

    /**
     * @param $folder
     * @param $filename
     * @return string
     */
    protected function buildPath($folder, $filename): string
    {
        $dir1 = substr($filename, 0, 2);
        $dir2 = substr($filename, 2, 2);

        return $folder . DIRECTORY_SEPARATOR . $dir1 . DIRECTORY_SEPARATOR . $dir2 . DIRECTORY_SEPARATOR . $filename;
    }
}