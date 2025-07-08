<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Cart;

use Silecust\WebShop\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class CartItemAddedEvent extends Event
{

    public function __construct(private readonly Product $product,
                                private readonly int     $quantity)
    {
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