<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image;

use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertStringContainsString;


readonly class CategoryImageOperation
{

    public function __construct(
        private FilePhysicalOperation              $filePhysicalOperation,
        private CategoryDirectoryImagePathProvider $categoryDirectoryImagePathProvider)
    {


    }


    /**
     * Caution: This updates filename in file entity
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function createOrReplaceFileAndUpdateEntity(CategoryImage $categoryImage, UploadedFile $uploadedFile): File
    {

        $dir = $this->getDirectoryPath($categoryImage);
        assertNotNull($dir);

        $fileName = $this->getFileName($categoryImage);
        assertNotNull($fileName);

        $fileName = $this->checkIfFilePropertiesUpdated($dir, $fileName, $uploadedFile, $categoryImage);

        return $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $dir);
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return string
     */
    public function getDirectoryPath(CategoryImage $categoryImage): string
    {
        return $this->categoryDirectoryImagePathProvider->getImageDirectoryPath($categoryImage->getCategory()->getId());
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return string|null
     */
    public function getFileName(CategoryImage $categoryImage): ?string
    {
        return $categoryImage->getFile()->getName();
    }

    /**
     * @param string $dir
     * @param string|null $fileName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return string|null
     */
    public function checkIfFilePropertiesUpdated(string $dir, ?string $fileName, UploadedFile $uploadedFile, CategoryImage $categoryImage): ?string
    {
        $extensionExistingFile = $this->getExtensionExistingFile($dir, $fileName);
        $extensionUploadedFile = $uploadedFile->getExtension();

        if ($extensionExistingFile !== $extensionUploadedFile) {
            $fileName = $this->resetFileName($fileName, $extensionExistingFile, $extensionUploadedFile);
            assertStringContainsString($extensionUploadedFile, $fileName);
            // remove old file
            $this->filePhysicalOperation->removeFile($dir . '/' . $fileName);
            // update category Image
            $this->updateCategoryImage($categoryImage, $fileName);

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
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param string $fileName
     * @return void
     */
    public function updateCategoryImage(CategoryImage $categoryImage, string $fileName): void
    {
        $categoryImage->getFile()->setName($fileName);
    }

}