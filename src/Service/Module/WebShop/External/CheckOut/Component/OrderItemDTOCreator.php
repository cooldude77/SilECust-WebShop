<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\CheckOut\Component;

use Silecust\WebShop\Service\Module\WebShop\External\Cart\Order\Mapper\CartToOrderObjectDTOMapper;

class OrderItemDTOCreator
{
    public function __construct(private CartToOrderObjectDTOMapper $cartToOrderObjectDTOMapper)
    {
    }

    public function create(): array
    {

        return $this->cartToOrderObjectDTOMapper->map();
    }
}