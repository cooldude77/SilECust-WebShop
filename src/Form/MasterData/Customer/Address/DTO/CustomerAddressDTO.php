<?php

namespace Silecust\WebShop\Form\MasterData\Customer\Address\DTO;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    public ?array $addressTypes = [];
    public ?array $addressTypeDefaults = [];
    public ?int $postalCodeId = 0;

    /** @var string|null
     * For edit form
     */
    public ?string $currentPostalCodeText = null;


    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        /** @var CustomerAddressDTO $object */
        $object = $context->getObject();
        $addressTypes = $object->addressTypes;
        $addressTypeDefaults = $object->addressTypeDefaults;

        if (!in_array('shipping', $addressTypes))
            if (!in_array('billing', $addressTypes))
                $context->buildViolation('Address type should be either of shipping or billing')
                    ->atPath('addressTypes')
                    ->addViolation();
        if (in_array('useAsDefaultShipping', $addressTypeDefaults))
            if (!in_array('shipping', $addressTypes))
                $context->buildViolation('Please choose shipping address type')
                    ->atPath('addressTypes')
                    ->addViolation();
        if (in_array('useAsDefaultBilling', $addressTypeDefaults))
            if (!in_array('billing', $addressTypes))
                $context->buildViolation('Please choose billing address type')
                    ->atPath('addressTypes')
                    ->addViolation();
    }
}