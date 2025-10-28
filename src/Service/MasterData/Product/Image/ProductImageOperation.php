<?php

namespace Silecust\WebShop\Service\MasterData\Product\Image;

use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertStringContainsString;


readonly class ProductImageOperation
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
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function createOrReplaceFileAndUpdateEntity(ProductImage $productImage, UploadedFile $uploadedFile): File
    {

        $dir = $this->getDirectoryPath($productImage);
        assertNotNull($dir);

        $fileName = $this->getFileName($productImage);
        assertNotNull($fileName);

        $fileName = $this->checkIfFilePropertiesUpdated($dir, $fileName, $uploadedFile, $productImage);

        return $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $dir);
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return string
     */
    public function getDirectoryPath(ProductImage $productImage): string
    {
        return $this->productDirectoryImagePathProvider->getImageDirectoryPath($productImage->getProduct()->getId());
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return string|null
     */
    public function getFileName(ProductImage $productImage): ?string
    {
        return $productImage->getFile()->getName();
    }

    /**
     * @param string $dir
     * @param string|null $fileName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @return string|null
     */
    public function checkIfFilePropertiesUpdated(string $dir, ?string $fileName, UploadedFile $uploadedFile, ProductImage $productImage): ?string
    {
        $extensionExistingFile = $this->getExtensionExistingFile($dir, $fileName);
        $extensionUploadedFile = $uploadedFile->getExtension();

        if ($extensionExistingFile !== $extensionUploadedFile) {
            $fileName = $this->resetFileName($fileName, $extensionExistingFile, $extensionUploadedFile);
            assertStringContainsString($extensionUploadedFile, $fileName);
            // remove old file
            $this->filePhysicalOperation->copyFileAndMakeATempDeletedFile($dir . '/' . $fileName);
            // update product Image
            $this->updateProductImage($productImage, $fileName);

        }
        return $fileName;
    }

    /**
     * @param string $dir
     * @param string|null $fileName
     * @return string
     */
    public function getExtensionExistingFile(string $dir, ?string $fileName): string
    {
        return (new SplFileInfo($dir . '/' . $fileName))->getExtension();
    }

    /**
     * @param string|null $fileName
     * @param string $extensionExistingFile
     * @param string $extensionUploadedFile
     * @return string
     */
    public function resetFileName(?string $fileName, string $extensionExistingFile, string $extensionUploadedFile): string
    {
        // reset file name
        return substr($fileName, 0, strlen($fileName) - strlen($extensionExistingFile))
            . $extensionUploadedFile;
    }

    /**
     * @param \Silecust\WebShop\Entity\ProductImage $productImage
     * @param string $fileName
     * @return void
     */
    public function updateProductImage(ProductImage $productImage, string $fileName): void
    {
        $productImage->getFile()->setName($fileName);
    }

}