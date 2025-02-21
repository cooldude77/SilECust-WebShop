<?php

namespace Silecust\WebShop\Service\Module\WebShop\Admin\File;


use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Silecust\WebShop\Service\Common\File\Provider\Interfaces\DirectoryPathProviderInterface;

/**
 *  Directory Structure:
 *
 *  WebShop: Base Kernel Dir/public/files/webShops/{id}/{filename.extension}
 */
class WebShopFileDirectoryPathNamer extends AbstractFileDirectoryPathProvider implements DirectoryPathProviderInterface
{


    public function getFileFullPath(array $params): string
    {
        return "";            //$this->getProjectDir() . $this->getFileBaseDirPathSegment() .
        // '/webShops/'
        //.$params['webShopId'].'/images/';
    }

    public function getBaseFolderPath(): string
    {
        // TODO: Implement getBaseFolderPath() method.
        return "";
    }
}