<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Symfony\Contracts\EventDispatcher\Event;

class CheckoutAddressCreatedEvent extends Event
{

    public function __construct(private readonly Customer $customer,
        private readonly CustomerAddress $customerAddress,
        private readonly bool $isChosen = false
    ) {
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