<?php

namespace App\MediaUploader\Handlers;

use App\MediaUploader\Interfaces\MediaStorageInterface;
use App\MediaUploader\Interfaces\UploaderInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;
use Illuminate\Http\UploadedFile;

/**
 * Class FromRequestUploader
 * @package App\MediaUploader\Handlers
 */
class FromRequestUploader implements UploaderInterface
{
    /**
     * @var MediaStorageInterface
     */
    protected $storage;

    /**
     * FromRequestUploader constructor.
     * @param MediaStorageInterface $storage
     */
    public function __construct(MediaStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $fileOrUrls
     * @param string $folder
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function save($fileOrUrls, string $folder)
    {
        $files = [];

        foreach ($fileOrUrls as $uploadedFile) {
            /** @var UploadedFile $uploadedFile */

            $filename = $this->getFileName($uploadedFile->getClientOriginalName());

            $path = $folder . DIRECTORY_SEPARATOR . $filename;

            if ($fileContent = $uploadedFile->get()) {
                if ($this->storage->put($path, $fileContent)) {
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
        }

        return $files;
    }

    /**
     * @param $name
     * @return string
     */
    private function getFileName($name)
    {
        $parts = explode('.', $name);

        return md5($name) . "." . end($parts);
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
