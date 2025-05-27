<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Address;

use Silecust\WebShop\Entity\CustomerAddress;
use Symfony\Contracts\EventDispatcher\Event;

class AddressChosenEvent extends Event
{
    public const string EVENT_NAME = 'checkout.post.address_chosen';

    public function __construct(private readonly CustomerAddress $customerAddress)
    {
    }

    public function getCustomerAddress(): CustomerAddress
    {
        return $this->customerAddress;
    }


}