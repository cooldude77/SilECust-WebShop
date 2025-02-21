<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image\Provider;

use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Silecust\WebShop\Service\Common\File\Provider\Interfaces\DirectoryPathProviderInterface;

/**
 *  Directory Structure:
 *
 *  Category: Base Kernel Dir/public/files/Categorys/{id}/{filename.extension}
 */
class CategoryDirectoryPathProvider extends AbstractFileDirectoryPathProvider implements DirectoryPathProviderInterface
{

    private string $ownPathSegment = '/category';


    public function getBaseFolderPath(): string
    {
     return    $this->getPhysicalFilePathForFiles(). $this->ownPathSegment;
    }

    /**
     * @return string
     * Provides complete directory path ( but not the file name )
     */
    protected function getPhysicalFilePathForFiles(): string
    {

        return parent::getPhysicalFilePathForFiles().$this->ownPathSegment;
    }

}