<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Symfony\Contracts\EventDispatcher\Event;

class AddressCreatedEvent extends Event
{

    public const string EVENT_NAME = 'checkout.post.address_created';

    public function __construct(private readonly Customer        $customer,
                                private readonly CustomerAddress $customerAddress,
                                private readonly bool            $isChosen = false
    )
    {
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getCustomerAddress(): CustomerAddress
    {
        return $this->customerAddress;
    }

    public function isChosen(): bool
    {
        return $this->isChosen;
    }

}