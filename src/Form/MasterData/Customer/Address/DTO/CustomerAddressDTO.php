<?php

namespace App\Form\MasterData\Customer\Address\DTO;


/**
 * Note: We cannot completely create a DTO is not having a domain object
 * because Entity Type will not create a dropdown if we use just an int
 */
class CustomerAddressDTO
{
    public int $id = 0;
    public int $customerId = 0;

    public ?string $line1= null;
    public ?int $pinCodeId = 0;

}