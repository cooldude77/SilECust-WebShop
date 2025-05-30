<?php

namespace Silecust\WebShop\Service\MasterData\Customer\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;

class CustomerAddressQuery
{

    public function __construct(
        private readonly CustomerAddressRepository $customerAddressRepository) {
    }

    public function getAddressInASingleLine(int $id): string
    {
        $customerAddress = $this->customerAddressRepository->find($id);
        return $customerAddress->getLine1() . "\n"
            . ($customerAddress->getLine2() != null ? $customerAddress->getLine2() . "\n" : "")
            . ($customerAddress->getLine3() != null ? $customerAddress->getLine3() . "\n" : "")
            . $customerAddress->getCode()->getCity()->getName() . "\n"
            . $customerAddress->getCode()->getCity()->getState()->getName() . "\n"
            . $customerAddress->getCode()->getCity()->getState()->getCountry()->getName() . "\n"
            . $customerAddress->getCode()->getCode() . "\n";


    }


}