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
    private MockObject $filesystemMock;
    private FilePhysicalOperation $fileOperation;

    public function testCreateOrReplaceFile_ShouldMoveFileAndReturnFile()
    {
        // Arrange
        $directory = '/tmp/uploads';
        $fileName = 'test-file.txt';

        // Mock UploadedFile
        $uploadedFileMock = $this->createMock(UploadedFile::class);

        $mockFile = $this->createMock(File::class);

        $uploadedFileMock->expects($this->once())
            ->method('move')
            ->with($directory, $fileName)
            ->willReturn($mockFile);

        // Act
        $result = $this->fileOperation->createOrReplaceFile($uploadedFileMock, $fileName, $directory);

        // Assert
        $this->assertSame($mockFile, $result);
    }

    public function testRemoveFile_ShouldCallFilesystemRemove()
    {
        // Arrange
        $filePath = '/tmp/uploads/test-file.txt';

        // Expect the remove method to be called once with the file path
        $this->filesystemMock->expects($this->once())
            ->method('remove')
            ->with($filePath);

        // Act
        $this->fileOperation->removeFile($filePath);
    }

    protected function setUp(): void
    {
        // Mock the Filesystem dependency
        $this->filesystemMock = $this->createMock(Filesystem::class);
        // Instantiate the class with mocked filesystem
        $this->fileOperation = new FilePhysicalOperation($this->filesystemMock);
    }
}