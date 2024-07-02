<?php

namespace App\Event\Module\WebShop\External\Address;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Symfony\Contracts\EventDispatcher\Event;

class CheckoutAddressChosenEvent extends Event
{
    public function __construct(private readonly Customer $customer,
        private readonly CustomerAddress $customerAddress
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

}