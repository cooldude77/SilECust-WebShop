<?php

namespace App\Service\File;

use App\Form\Common\File\DTO\FileFormDTO;
use App\Form\Common\File\Mapper\FileDTOMapper;
use App\Service\File\Interfaces\FileDirectoryPathNamerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{

    public function moveFile(FileDirectoryPathNamerInterface $fileDirectoryPathNamer, UploadedFile $fileHandle, string $fileName, array $params): File
    {
        $path = $fileDirectoryPathNamer->getFileFullPathImage($params);
        return $fileHandle->move($path, $fileName);
    }

}