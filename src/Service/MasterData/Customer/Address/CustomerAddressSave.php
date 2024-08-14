<?php

namespace App\Service\MasterData\Customer\Address;

use App\Entity\CustomerAddress;
use App\Service\Component\Database\DatabaseOperations;

class CustomerAddressSave
{

    public function __construct(private readonly DatabaseOperations $databaseOperations)
    {
    }

    public function save(CustomerAddress $customerAddress): CustomerAddress
    {

        $this->databaseOperations->persist($customerAddress);
        $this->databaseOperations->flush();

        return $customerAddress;
    }


}