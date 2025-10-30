<?php

namespace Silecust\WebShop\Tests\Service\Common\File;

use PHPUnit\Framework\TestCase;
use Silecust\WebShop\Exception\Common\File\FileDoesNotExist;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePhysicalOperationTest extends TestCase
{
    private Filesystem $filesystem;
    private FilePhysicalOperation $fileOperation;

    public function testCreateOrReplaceFile(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->expects($this->once())
            ->method('move')
            ->with('/target/dir', 'testfile.txt');

        $this->fileOperation->createOrReplaceFile($uploadedFile, 'testfile.txt', '/target/dir');
    }

    public function testCopyFileAndMakeATempDeletedFile(): void
    {
        $originalFile = '/path/to/file.txt';
        $tempFile = $originalFile . '.deleted';

        $this->filesystem->expects($this->once())
            ->method('copy')
            ->with($originalFile, $tempFile);

        $this->filesystem->expects($this->once())
            ->method('remove')
            ->with($originalFile);

        $result = $this->fileOperation->copyFileAndMakeATempDeletedFile($originalFile);
        $this->assertEquals($tempFile, $result);
    }

    public function testCopy(): void
    {
        $this->filesystem->expects($this->once())
            ->method('copy')
            ->with('/tmp/source.txt', '/dest/target.txt');

        $this->fileOperation->copy('/tmp/source.txt', '/dest/target.txt');
    }

    public function testHasFileNameChanged_ReturnsTrueWhenExtensionsDiffer(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('getClientOriginalExtension')->willReturn('png');

        $this->filesystem->method('exists')->willReturn(true);
        $result = $this->fileOperation->hasFileNameChanged('/dir', 'file.jpg', $uploadedFile);
        $this->assertTrue($result);
    }

    public function testHasFileNameChanged_ReturnsFalseWhenExtensionsMatch(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('getClientOriginalExtension')->willReturn('jpg');

        $this->filesystem->method('exists')->willReturn(true);

        $result = $this->fileOperation->hasFileNameChanged('/dir', 'file.jpg', $uploadedFile);
        $this->assertFalse($result);
    }

    public function testGetExtensionOfExistingFile_FileExists_ReturnsExtension(): void
    {
        $dir = sys_get_temp_dir();
        $fileName = 'testfile.txt';
        $filePath = $dir . DIRECTORY_SEPARATOR . $fileName;
        touch($filePath);

        $this->filesystem->method('exists')->willReturn(true);

        $extension = $this->fileOperation->getExtensionOfExistingFile($dir, $fileName);
        $this->assertEquals('txt', $extension);

        unlink($filePath);
    }

    public function testGetExtensionOfExistingFile_FileDoesNotExist_ThrowsException(): void
    {
        $this->expectException(FileDoesNotExist::class);
        $this->fileOperation->getExtensionOfExistingFile('/non/existent/dir', 'nofile.txt');
    }

    public function testGetFileNameUsingNewExtension(): void
    {
        $existingFileName = 'photo.jpg';
        $newExtension = 'png';

        $result = $this->fileOperation->getFileNameUsingNewExtension($existingFileName, 'jpg', $newExtension);
        $this->assertEquals('photo.png', $result);
    }

    public function testCleanupDeleteFile(): void
    {
        $fileName = '/path/to/delete.txt';

        $this->filesystem->expects($this->once())
            ->method('remove')
            ->with($fileName);

        $this->fileOperation->cleanupDeleteFile($fileName);
    }

    public function testExists_ReturnsTrueWhenFileExists(): void
    {
        $directory = '/some/dir';
        $fileName = 'file.txt';

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($directory . DIRECTORY_SEPARATOR . $fileName)
            ->willReturn(true);

        $result = $this->fileOperation->exists($directory, $fileName);
        $this->assertTrue($result);
    }

    public function testExists_ReturnsFalseWhenFileDoesNotExist(): void
    {
        $directory = '/some/dir';
        $fileName = 'file.txt';

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($directory . DIRECTORY_SEPARATOR . $fileName)
            ->willReturn(false);

        $result = $this->fileOperation->exists($directory, $fileName);
        $this->assertFalse($result);
    }

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->fileOperation = new FilePhysicalOperation($this->filesystem);
    }
}