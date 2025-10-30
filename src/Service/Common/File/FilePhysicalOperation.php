<?php

namespace Silecust\WebShop\Service\Common\File;

use Silecust\WebShop\Exception\Common\File\FileDoesNotExist;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\assertNotNull;

readonly class FilePhysicalOperation
{

    public function __construct(private Filesystem $filesystem)
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $fileHandle
     * @param string $fileName
     * @param string $directoryForFileToBeMoved
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function createOrReplaceFile(UploadedFile $fileHandle,
                                        string       $fileName,
                                        string       $directoryForFileToBeMoved): void
    {
        $fileHandle->move($directoryForFileToBeMoved, $fileName);
    }

    /**
     * @param string $filePathAndName
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @noinspection PhpDocSignatureIsNotCompleteInspection
     */
    public function copyFileAndMakeATempDeletedFile(string $filePathAndName): string
    {
        // store with a new name
        $tempFilename = $filePathAndName . '.deleted';
        $this->filesystem->copy($filePathAndName, $tempFilename);
        // remove
        $this->filesystem->remove($filePathAndName);
        return $tempFilename;
    }

    /**
     * @param mixed $tmpFile
     * @param string $originalFile
     * @return void
     * @noinspection PhpDocSignatureIsNotCompleteInspection
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function copy(mixed $tmpFile, string $originalFile): void
    {
        $this->filesystem->copy($tmpFile, $originalFile);
    }


    /**
     * @param string $dir
     * @param string|null $fileName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return bool
     * @throws \Silecust\WebShop\Exception\Common\File\FileDoesNotExist
     */
    public
    function hasFileNameChanged(string $dir, ?string $fileName, UploadedFile $uploadedFile): bool
    {
        $extensionExistingFile = $this->getExtensionOfExistingFile($dir, $fileName);
        // do not use getExtension() function
        // note: https://github.com/symfony/symfony/issues/7613
        $extensionUploadedFile = $uploadedFile->getClientOriginalExtension();

        return $extensionExistingFile !== $extensionUploadedFile;
    }

    /**
     * @param string $dir
     * @param string|null $fileName
     * @return string
     * @throws  FileDoesNotExist
     */
    public
    function getExtensionOfExistingFile(string $dir, ?string $fileName): string
    {
        if (!file_exists($dir . DIRECTORY_SEPARATOR . $fileName))
            throw new FileDoesNotExist($dir, $fileName);

        return (new SplFileInfo($dir . '/' . $fileName))->getExtension();
    }

    /**
     * @param string|null $existingFileName
     * @param string $extensionOfExistingFile
     * @param string $extensionOfUploadedFile
     * @return string
     */
    public
    function getFileNameUsingNewExtension(string $existingFileName,
                                          string $extensionOfExistingFile,
                                          string $extensionOfUploadedFile): string
    {

        assertNotNull($existingFileName);
        assertNotNull($extensionOfExistingFile);
        assertNotNull($extensionOfUploadedFile);

        // reset file name
        return substr($existingFileName, 0, strlen($existingFileName) - strlen($extensionOfExistingFile))
            . $extensionOfUploadedFile;
    }

    /**
     * @param string $fileName
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function cleanupDeleteFile(string $fileName): void
    {
        if ($fileName != null)
            $this->filesystem->remove($fileName);
    }

    public function exists(string $directory, ?string $fileName): bool
    {
        return $this->filesystem->exists($directory . DIRECTORY_SEPARATOR . $fileName);
    }
}