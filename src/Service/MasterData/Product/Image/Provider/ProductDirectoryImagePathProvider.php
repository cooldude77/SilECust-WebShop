<?php

namespace App\Service\MasterData\Product\Image\Provider;

use App\Entity\Product;

/**
 *  Directory Structure:
 *
 *  Product: Base Kernel Dir/public/files/Products/{id}/{filename.extension}
 */
class ProductDirectoryImagePathProvider extends ProductDirectoryPathProvider
{


    private string $ownPathSegment = '/images';


    public function getImageDirectoryPath(int $id):string
    {
        // product/id/images/
        return  $this->getPhysicalFilePathForFiles(). "/{$id}{$this->ownPathSegment}/";
    }

    public function getFullPhysicalPathForFileByName(Product $product, string $fileName): string
    {
        // product/id/images/filename
        return $this->getImageDirectoryPath($product->getId()).$fileName;
    }

}