<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Cart;

use Silecust\WebShop\Entity\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CartClearedByUserEvent extends Event
{

    public function __construct(private $customer)
    {
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }


}