<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Address\New\DTO;

use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;

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