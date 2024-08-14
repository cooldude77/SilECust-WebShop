<?php

namespace App\Form\Module\WebShop\External\Address\Existing\DTO;

/**
 * For using the form when there are multiple billing/shipping addresses
 */
class AddressChooseExistingSingleDTO
{
    public ?string $address = null;
    public bool $isChosen = false;
    public int $id = 0;
}