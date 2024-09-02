<?php

namespace App\Tests\Service\File;

use App\Service\Common\File\Provider\FileDirectoryPathProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class FileGeneralDirectoryPathNamerTest extends KernelTestCase
{

    public function testGetFileFullPath()
    {
        self::bootKernel();
        $kernel = static::$kernel;
        $kernel->getProjectDir();
        $namer = new FileDirectoryPathProvider($kernel->getProjectDir(), static::$kernel->getContainer()->getParameter('file_storage_path'));

        $expected = static::$kernel->getProjectDir() . '/data/test/uploads/general/file_name.xyz';
        assertEquals($namer->getFullPathForImageFile('file_name.xyz'), $expected);
    }
}
