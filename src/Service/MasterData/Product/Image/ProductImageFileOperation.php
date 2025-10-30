<?php

namespace Silecust\WebShop\Service\MasterData\Product\Image;

use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotNull;


class ProductImageFileOperation
{

    public function __construct(
        private FilePhysicalOperation             $filePhysicalOperation,
        private ProductDirectoryImagePathProvider $productDirectoryImagePathProvider)
    {


    }


    /**
     * Caution: This updates filename in file entity
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return void
     */
    public function createOrUpdateFileAndEntity(ProductImage $productImage, UploadedFile $uploadedFile): void
    {

        $directory = $this->getDirectory($productImage);
        assertNotNull($directory);

        $fileName = $this->getImageFileName($productImage);;
        assertNotNull($fileName);

        // in case of change of file extension
        $toBeDeletedFileName = '';

        // it may be now be a png instead of a jpg
        if ($this->fileExists($directory, $fileName) && $this->hasFileNameChanged($directory, $fileName, $uploadedFile)) {

            // get same file name but with extension changed
            $oldFileName = $fileName;
            $fileName = $this->getFileNameUsingNewExtension($fileName, $directory, $uploadedFile);

            // update product Image
            $this->updateEntity($productImage, $fileName);

            // delete file with old filename
            $toBeDeletedFileName = $this->deleteFile($directory, $oldFileName);

        }

        $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $directory);

        $this->filePhysicalOperation->cleanupDeleteFile($toBeDeletedFileName);
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return string
     */
    public function getDirectory(ProductImage $productImage): string
    {
        return $this->productDirectoryImagePathProvider->getImageDirectoryPath($this->getId($productImage));
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return int|null
     */
    public function getId(ProductImage $productImage): ?int
    {
        return $productImage->getProduct()->getId();
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return string|null
     */
    public function getImageFileName(ProductImage $productImage): ?string
    {
        return $productImage->getFile()->getName();
    }

    private function fileExists(string $directory, ?string $fileName): bool
    {
        return $this->filePhysicalOperation->exists($directory, $fileName);
    }

    /**
     * @param string $directory
     * @param string|null $fileName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return bool|string|null
     */
    public function hasFileNameChanged(string $directory, ?string $fileName, UploadedFile $uploadedFile): string|bool|null
    {
        return $this->filePhysicalOperation->hasFileNameChanged($directory, $fileName, $uploadedFile);
    }

    /**
     * @param string|null $fileName
     * @param string $directory
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return string
     * @throws \Silecust\WebShop\Exception\Common\File\FileDoesNotExist
     */
    public function getFileNameUsingNewExtension(?string $fileName, string $directory, UploadedFile $uploadedFile): string
    {
        return $this->filePhysicalOperation->getFileNameUsingNewExtension(
            $fileName,
            $this->filePhysicalOperation->getExtensionOfExistingFile($directory, $fileName),
            $uploadedFile->getClientOriginalExtension());
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param string $fileName
     * @return void
     */
    public function updateEntity(ProductImage $productImage, string $fileName): void
    {
        $productImage->getFile()->setName($fileName);
    }

    /**
     * @param string $directory
     * @param string|null $fileName
     * @return string
     */
    public function deleteFile(string $directory, ?string $fileName): string
    {
        return $this->filePhysicalOperation->copyFileAndMakeATempDeletedFile($directory . '/' . $fileName);
    }


}