<?php

namespace App\Service\Module\WebShop\External\Order;

use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use App\Service\Transaction\Order\Object\OrderItemObject;

readonly class OrderToCart
{
    public function __construct(private CartSessionProductService $cartSessionProductService
    ) {
    }

    public function copyProductsFromOrderToCart(array $orderItemObjects): void
    {

        /** @var OrderItemObject $item */
        foreach ($orderItemObjects as $item) {

            // todo: check if product exists any more ,
            // todo: check if price changed
            // todo: check quantity availability
            // todo: separate service for above?
            // to avoid event chaining store errors in session ???

            $this->cartSessionProductService->addItemToCart(
                new CartSessionObject
                (
                    $item->getOrderItem()->getProduct()->getId(),
                    $item->getOrderItem()->getQuantity()
                )
            );

        }


    }
}