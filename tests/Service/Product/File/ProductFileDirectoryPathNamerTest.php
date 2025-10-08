<?php

namespace Silecust\WebShop\Tests\Service\Product\File;

use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use function PHPUnit\Framework\assertEquals;

class ProductFileDirectoryPathNamerTest extends KernelTestCase
{

    use ProductFixture,Factories;

    public function testGetFileFullPath()
    {
        $this->createProductFixtures();
        self::bootKernel();
        $namer = new ProductDirectoryImagePathProvider(
            static::$kernel->getProjectDir(),
            static::$kernel->getContainer()->getParameter('file_storage_path'),
            static::$kernel->getContainer()->getParameter('uploads_segment'));

        $expected = static::$kernel->getProjectDir() . "/data/test/uploads/product/{$this->product1->getId()}/images/file_name.png";
        assertEquals($expected, $namer->getFullPhysicalPathForFileByName($this->product1->object(), 'file_name.png'));
    }
}