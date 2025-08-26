<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Order;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Item\CartItem;

readonly class OrderToCart
{
    public function __construct(private CartProductManager $cartSessionProductService
    ) {
    }

    /**
     * @param array $orderItems
     * @return void
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart
     */
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
                new CartItem
                (
                    $item->getProduct()->getId(),
                    $item->getQuantity()
                )
            );

        }


    }
}