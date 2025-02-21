<?php

namespace Silecust\WebShop\Form\MasterData\Category\Image\DTO;

use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * DTO should contain no objects of entity type
 */
class CategoryImageDTO
{

    public ?FileDTO $fileDTO  = null;

    public int $categoryId = 0;

    public int $minWidth = 0;
    public int $minHeight= 0;

    public function __construct()
    {
        $this->fileDTO = new FileDTO();
    }



    public function getFileName():string
    {
        return $this->fileDTO->name;
    }

    public function getUploadedFile():UploadedFile
    {
        return $this->fileDTO->uploadedFile;
    }


}