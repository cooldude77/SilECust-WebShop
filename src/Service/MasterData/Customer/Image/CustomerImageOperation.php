<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Image;

use Silecust\WebShop\Entity\CustomerImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Customer\Image\Provider\CustomerDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;


class CustomerImageOperation
{

    private CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider;
    private FilePhysicalOperation $filePhysicalOperation;

    public function __construct( FilePhysicalOperation $filePhysicalOperation, CustomerDirectoryImagePathProvider $customerDirectoryImagePathProvider)
    {


        $this->customerDirectoryImagePathProvider = $customerDirectoryImagePathProvider;
        $this->filePhysicalOperation = $filePhysicalOperation;
    }


    public function createOrReplace(CustomerImage $customerImage,UploadedFile $uploadedFile): File
    {

        assertNotEquals($customerImage->getCustomer()->getId(),0);

        $dir = $this->customerDirectoryImagePathProvider->getImageDirectoryPath($customerImage->getCustomer()->getId());

        assertNotNull($dir);

        $fileName = $customerImage->getFile()->getName();

        assertNotNull($fileName);

        return $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $dir);
    }

}