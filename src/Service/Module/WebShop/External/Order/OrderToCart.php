<?php

namespace App\Service\Module\WebShop\External\Order;

use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;

readonly class OrderToCart
{
    public function __construct(private CartSessionProductService $cartSessionProductService
    ) {
    }

    public function copyProductsFromOrderToCart(array $orderItems): void
    {

        /** @var OrderItem $item */
        foreach ($orderItems as $item) {

            // todo: check if product exists any more ,
            // todo: check if price changed
            // todo: check quantity availability
            // todo: separate service for above?
            // to avoid event chaining store errors in session ???

            $this->cartSessionProductService->addItemToCart(
                new CartSessionObject
                (
                    $item->getProduct()->getId(),
                    $item->getQuantity()
                )
            );

        }


    }
}