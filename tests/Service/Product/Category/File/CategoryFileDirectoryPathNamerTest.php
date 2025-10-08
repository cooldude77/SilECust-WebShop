<?php

namespace Silecust\WebShop\Tests\Service\Product\Category\File;

use Silecust\WebShop\Service\MasterData\Category\Image\Provider\CategoryDirectoryImagePathProvider;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use function PHPUnit\Framework\assertEquals;

class CategoryFileDirectoryPathNamerTest extends KernelTestCase
{
    use ProductFixture,Factories;

    public function testGetFileFullPath()
    {
        $this->createProductFixtures();
        self::bootKernel();
        $namer = new CategoryDirectoryImagePathProvider(
            static::$kernel->getProjectDir(),
            static::$kernel->getContainer()->getParameter('file_storage_path'),
            static::$kernel->getContainer()->getParameter('uploads_segment')
        );

        $expected = static::$kernel->getProjectDir() . "/data/test/uploads/category/{$this->category1->getId()}/images/file_name.jpg";
        assertEquals($expected, $namer->getFullPhysicalPathForFileByName($this->category1->object(), 'file_name.jpg'));
    }
}
