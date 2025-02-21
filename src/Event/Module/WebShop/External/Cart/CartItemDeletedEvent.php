<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Cart;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class CartItemDeletedEvent extends Event
{

    public function __construct(private $customer,private  Product $product)
    {
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }


}