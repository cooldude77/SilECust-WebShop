<?php

namespace App\Service\MasterData\Customer\Address;

use App\Entity\CustomerAddress;
use App\Repository\CustomerAddressRepository;
use App\Service\Component\Database\DatabaseOperations;

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
            . $customerAddress->getPostalCode()->getCity()->getName() . "\n"
            . $customerAddress->getPostalCode()->getCity()->getState()->getName() . "\n"
            . $customerAddress->getPostalCode()->getCity()->getState()->getCountry()->getName() . "\n"
            . $customerAddress->getPostalCode()->getPostalCode() . "\n";


    }


}