<?php

namespace Silecust\WebShop\Service\Common\File\Provider;

use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Silecust\WebShop\Service\Common\File\Provider\Interfaces\DirectoryPathProviderInterface;

/**
 *  Directory Structure:
 *
 *  Product: Base Kernel Dir/public/files/products/{id}/{filename.extension}
 */


class FileDirectoryPathProvider extends AbstractFileDirectoryPathProvider
implements DirectoryPathProviderInterface
{

    /**
     * @var string
     * All files without connection to any specific entity
     * reside in this folder
     */
    private string $ownPathSegment = '/general';


    /**
     * @return string
     * Doesn't include file name
     * will return '/public/uploads/general'
     */
    public function getFullPathForImageFile(string $fileName): string
    {
        return $this->getBaseFolderPath() . '/'.$fileName;
    }


    /**
     * @param array $params
     * @return string
     * Doesn't include file name
     * will return '/public/uploads/general'
     */
    public function getBaseFolderPath(): string
    {
        return $this->getPhysicalFilePathForFiles() . $this->ownPathSegment;
    }


}