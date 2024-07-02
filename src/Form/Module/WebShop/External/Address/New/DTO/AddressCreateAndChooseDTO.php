<?php

namespace App\Form\Module\WebShop\External\Address\New\DTO;

use App\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;

/**
 * Create and choose from the form
 */
class AddressCreateAndChooseDTO
{

    public CustomerAddressDTO $address;
    public bool $isChosen = false;

    public function __construct()
    {
        $this->address = new CustomerAddressDTO();
    }
}