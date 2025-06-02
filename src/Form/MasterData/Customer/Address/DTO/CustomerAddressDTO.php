<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\DTO;


/**
 * Note: We cannot completely create a DTO is not having a domain object
 * because Entity Type will not create a dropdown if we use just an int
 */
class CustomerAddressDTO
{
    public int $id = 0;
    public int $customerId = 0;

    public ?string $line1 = null;
    public ?string $line2 = null;
    public ?string $line3 = null;

    public ?string $addressType = "billing";
    public ?int $postalCodeId = 0;

    /** @var string|null
     * For edit form
     */
    public ?string $currentPostalCodeText = null;
    public bool $isDefault = false;

}