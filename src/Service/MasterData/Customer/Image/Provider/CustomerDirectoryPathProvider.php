<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Image\Provider;

use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Silecust\WebShop\Service\Common\File\Provider\Interfaces\DirectoryPathProviderInterface;

/**
 *  Directory Structure:
 *
 *  Customer: Base Kernel Dir/public/files/Customers/{id}/{filename.extension}
 */
class CustomerDirectoryPathProvider extends AbstractFileDirectoryPathProvider implements DirectoryPathProviderInterface
{

    private string $ownPathSegment = '/customer';


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