<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image;

use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotNull;


readonly class CategoryImageFileOperation
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
     * @return void
     */
    public function createOrUpdateFileAndEntity(CategoryImage $categoryImage, UploadedFile $uploadedFile): void
    {

        $directory = $this->getDirectory($categoryImage);
        assertNotNull($directory);

        $fileName = $this->getImageFileName($categoryImage);;
        assertNotNull($fileName);

        // in case of change of file extension
        $toBeDeletedFileName = '';

        // it may be now be a png instead of a jpg
        if ($this->hasFileNameChanged($directory, $fileName, $uploadedFile)) {
            // delete file with old filename
            $toBeDeletedFileName = $this->deleteFile($directory, $fileName);

            // get same file name but with extension changed
            $fileName = $this->getFileNameUsingNewExtension($fileName, $directory, $uploadedFile);

            // update category Image
            $this->updateEntity($categoryImage, $fileName);

        }

        $this->filePhysicalOperation->createOrReplaceFile($uploadedFile, $fileName, $directory);

        $this->filePhysicalOperation->cleanupDeleteFile($toBeDeletedFileName);
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return string
     */
    public function getDirectory(CategoryImage $categoryImage): string
    {
        return $this->categoryDirectoryImagePathProvider->getImageDirectoryPath($this->getId($categoryImage));
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return int|null
     */
    public function getId(CategoryImage $categoryImage): ?int
    {
        return $categoryImage->getCategory()->getId();
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @return string|null
     */
    public function getImageFileName(CategoryImage $categoryImage): ?string
    {
        return $categoryImage->getFile()->getName();
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
     * @param string $directory
     * @param string|null $fileName
     * @return string
     */
    public function deleteFile(string $directory, ?string $fileName): string
    {
        return $this->filePhysicalOperation->copyFileAndMakeATempDeletedFile($directory . '/' . $fileName);
    }

    /**
     * @param string|null $fileName
     * @param string $directory
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return string
     */
    public function getFileNameUsingNewExtension(?string $fileName, string $directory, UploadedFile $uploadedFile): string
    {
        return $this->filePhysicalOperation->getFileNameUsingNewExtension(
            $fileName,
            $this->filePhysicalOperation->getExtensionExistingFile($directory, $fileName),
            $uploadedFile->getExtension());
    }

    /**
     * @param \Silecust\WebShop\Entity\CategoryImage $categoryImage
     * @param string $fileName
     * @return void
     */
    public function updateEntity(CategoryImage $categoryImage, string $fileName): void
    {
        $categoryImage->getFile()->setName($fileName);
    }


}