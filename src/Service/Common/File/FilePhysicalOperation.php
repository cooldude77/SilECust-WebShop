<?php

namespace Silecust\WebShop\Service\Common\File;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePhysicalOperation
{

    public function createOrReplaceFile(UploadedFile $fileHandle,
                                        string       $fileName,
                                        string       $directoryForFileToBeMoved): File
    {
        return $fileHandle->move($directoryForFileToBeMoved, $fileName);
    }


}