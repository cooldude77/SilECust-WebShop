<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Image\Provider;

use Silecust\WebShop\Entity\Customer;

/**
 *  Directory Structure:
 *
 *  Customer: Base Kernel Dir/public/files/Customers/{id}/{filename.extension}
 */
class CustomerDirectoryImagePathProvider extends CustomerDirectoryPathProvider
{


    private string $ownPathSegment = '/images';


    public function getImageDirectoryPath(int $id):string
    {
        // customer/id/images/
        return  $this->getPhysicalFilePathForFiles(). "/{$id}{$this->ownPathSegment}/";
    }

    public function getFullPhysicalPathForFileByName(Customer $customer, string $fileName): string
    {
        // customer/id/images/filename
        return $this->getImageDirectoryPath($customer->getId()).$fileName;
    }

}