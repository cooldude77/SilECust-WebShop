<?php

namespace Silecust\WebShop\Service\MasterData\Product\Image\Provider;

use Silecust\WebShop\Entity\Product;

/**
 *  Directory Structure:
 *
 *  Product: Base Kernel Dir/public/files/Products/{id}/{filename.extension}
 */
class ProductDirectoryImagePathProvider extends ProductDirectoryPathProvider
{


    private string $ownPathSegment = 'images';

    public function getFullPhysicalPathForFileByName(Product $product, string $fileName): string
    {
        // product/id/images/filename
        return $this->getImageDirectoryPath($product->getId()) . $fileName;
    }

    public function getImageDirectoryPath(int $id): string
    {
        // product/id/images/
        return $this->getPhysicalFilePathForFiles() . "/$id/$this->ownPathSegment/";
    }

}