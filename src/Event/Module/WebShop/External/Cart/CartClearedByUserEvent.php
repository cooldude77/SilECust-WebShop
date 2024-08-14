<?php

namespace App\Event\Module\WebShop\External\Cart;

use App\Entity\Customer;
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