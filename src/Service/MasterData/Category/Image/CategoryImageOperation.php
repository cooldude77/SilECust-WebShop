<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image;

use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;


class CategoryImageOperation
{

    private CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider;
    private FilePhysicalOperation $filePhysicalOperation;

    public function __construct( FilePhysicalOperation $filePhysicalOperation, CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider)
    {


        $this->categoryDirectoryImagePathProvider = $categoryDirectoryImagePathProvider;
        $this->filePhysicalOperation = $filePhysicalOperation;
    }


    public function createOrReplace(CategoryImage $categoryImage,UploadedFile $uploadedFile): File
    {

        assertNotEquals($categoryImage->getCategory()->getId(),0);

        $dir = $this->categoryDirectoryImagePathProvider->getImageDirectoryPath($categoryImage->getCategory()->getId());

        assertNotNull($dir);

        $fileName = $categoryImage->getFile()->getName();

        assertNotNull($fileName);

        return $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $dir);
    }

}