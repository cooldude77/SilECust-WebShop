<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;

class CustomerAddressSave
{

    public function __construct(
        private CustomerAddressRepository   $customerAddressRepository,
        private readonly DatabaseOperations $databaseOperations)
    {
    }

    public function save(CustomerAddress $customerAddress): CustomerAddress
    {

        $this->databaseOperations->persist($customerAddress);
        $this->databaseOperations->flush();

        return $customerAddress;
    }

    public
    function setDefaultAddressForAddressNotIn(CustomerAddress $customerAddress)
    {
        $this->customerAddressRepository->setDefaultAddressForAddressNotIn($customerAddress);
    }

}