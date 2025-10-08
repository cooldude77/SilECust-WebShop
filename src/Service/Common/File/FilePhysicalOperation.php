<?php

namespace Silecust\WebShop\Service\Common\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FilePhysicalOperation
{

    public function __construct(private Filesystem $filesystem)
    {
    }

    public function createOrReplaceFile(UploadedFile $fileHandle,
                                        string       $fileName,
                                        string       $directoryForFileToBeMoved): File
    {
        return $fileHandle->move($directoryForFileToBeMoved, $fileName);
    }

    public function removeFile(string $filePathAndName): void
    {
        $this->filesystem->remove($filePathAndName);
    }

}