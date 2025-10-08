<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image\Provider;

use Silecust\WebShop\Entity\Category;

/**
 *  Directory Structure:
 *
 *  Category: Base Kernel Dir/public/files/Category/{id}/{filename.extension}
 */
class CategoryDirectoryImagePathProvider extends CategoryDirectoryPathProvider
{


    private string $componentLevelPathSegment = 'images';


    public function getImageDirectoryPath(int $id):string
    {
        // category/id/images/
        return  $this->getPhysicalFilePathForFiles(). "/$id/$this->componentLevelPathSegment/";
    }

    public function getFullPhysicalPathForFileByName(Category $category, string $fileName): string
    {
        // category/id/images/filename
        return $this->getImageDirectoryPath($category->getId()).$fileName;
    }

}