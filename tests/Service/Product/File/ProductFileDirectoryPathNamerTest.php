<?php

namespace App\Tests\Service\Product\File;

use App\Service\Product\Category\File\CategoryDirectoryPathProvider;
use App\Service\Product\File\ProductDirectoryPathProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class ProductFileDirectoryPathNamerTest  extends KernelTestCase
{

    public function testGetFileFullPath()
    {
        self::bootKernel();
        $namer = new ProductDirectoryPathProvider(static::$kernel);

        $expected =static::$kernel->getProjectDir().'/public/uploads/products/1/images';
        assertEquals($namer->getFullPathForImages(['id'=>1]),$expected);
    }
}