<?php

namespace App\Event\Module\WebShop\External\Cart;

use App\Entity\Customer;
use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class CartItemAddedEvent extends Event
{

    public function __construct(private $customer, private Product $product,
        private readonly int $quantity
    ) {
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }


}