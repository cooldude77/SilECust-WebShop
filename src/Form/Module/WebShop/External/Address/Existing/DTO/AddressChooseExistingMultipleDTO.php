<?php

namespace Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO;

/**
 * For using the form when there are multiple billing/shipping addresses
 */
class AddressChooseExistingMultipleDTO
{
    public array $addresses;

    public function add(AddressChooseExistingSingleDTO $dto): void
    {
        $this->addresses[] = $dto;
    }
}