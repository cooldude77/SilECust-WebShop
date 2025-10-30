<?php

namespace Silecust\WebShop\Tests\Service\Common\File;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FilePhysicalOperationTest extends TestCase
{
    private $filesystemMock;
    private $fileOperation;

    protected function setUp(): void
    {
        $this->filesystemMock = $this->createMock(Filesystem::class);
        $this->fileOperation = new FilePhysicalOperation($this->filesystemMock);
    }

    public function testCreateOrReplaceFileCallsMove(): void
    {
        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $directory = '/tmp/uploads';
        $fileName = 'testfile.txt';

        $uploadedFileMock->expects($this->once())
            ->method('move')
            ->with($directory, $fileName);

        $this->fileOperation->createOrReplaceFile($uploadedFileMock, $fileName, $directory);
    }

    public function testCopyFileAndMakeATempDeletedFile(): void
    {
        $originalFilePath = '/tmp/file.txt';
        $tempFilePath = $originalFilePath . '.deleted';

        $this->filesystemMock->expects($this->once())
            ->method('copy')
            ->with($originalFilePath, $tempFilePath);

        $this->filesystemMock->expects($this->once())
            ->method('remove')
            ->with($originalFilePath);

        $result = $this->fileOperation->copyFileAndMakeATempDeletedFile($originalFilePath);

        $this->assertEquals($tempFilePath, $result);
    }

    public function testCopyDelegatesToFilesystem(): void
    {
        $tmpFile = '/tmp/tmpfile.txt';
        $originalFile = '/tmp/original.txt';

        $this->filesystemMock->expects($this->once())
            ->method('copy')
            ->with($tmpFile, $originalFile);

        $this->fileOperation->copy($tmpFile, $originalFile);
    }

    public function testHasFileNameChangedReturnsTrueWhenExtensionsDiffer(): void
    {
        $dir = '/tmp';
        $fileName = 'testfile.txt';

        $uploadedFileMock = $this->createMock(UploadedFile::class);
        $uploadedFileMock->method('getExtension')->willReturn('pdf');

        // Mock getExtensionOfExistingFile to return 'txt'
        $reflection = new \ReflectionClass($this->fileOperation);
        $method = $reflection->getMethod('getExtensionOfExistingFile');
        $method->setAccessible(true);

        $extensionExistingFile = 'txt';
        $this->assertTrue($this->fileOperation->hasFileNameChanged($dir, $fileName, $uploadedFileMock));
    }

    public function testGetExtensionExistingFileReturnsExtension(): void
    {
        $dir = '/tmp';
        $fileName = 'file.test';

        $extension = $this->fileOperation->getExtensionOfExistingFile($dir, $fileName);
        $this->assertEquals('test', $extension);
    }

    public function testGetFileNameUsingNewExtension(): void
    {
        $existingFileName = 'document.txt';
        $newExtension = 'pdf';

        $result = $this->fileOperation->getFileNameUsingNewExtension($existingFileName, 'txt', $newExtension);
        $this->assertEquals('document.pdf', $result);
    }

    public function testCleanupDeleteFileRemovesFile(): void
    {
        $fileName = '/tmp/file.txt';

        $this->filesystemMock->expects($this->once())
            ->method('remove')
            ->with($fileName);

        $this->fileOperation->cleanupDeleteFile($fileName);
    }
}