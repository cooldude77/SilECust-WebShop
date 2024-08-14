<?php

namespace App\Form\Module\WebShop\External\Address\Existing\DTO;

/**
 * For using the form when there are multiple billing/shipping addresses
 */
class AddressChooseExistingMultipleDTO
{
    public array $addresses;

    public function add(AddressChooseExistingSingleDTO $dto)
    {
        $this->addresses[] = $dto;
    }
}