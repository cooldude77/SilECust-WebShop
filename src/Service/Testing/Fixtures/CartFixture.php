<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Symfony\Component\HttpFoundation\Session\Session;

trait CartFixture
{
    private function createSessionKey(Session $session): void
    {
        $session->set(CartSessionProductService::CART_SESSION_KEY, []);
        $session->save();
    }

    private function addProductToCart(Session $session, Product $product, int $quantity): void
    {
        $array = [$product->getId() => new CartSessionObject($product->getId(), 4)];

        $existing = $session->get(CartSessionProductService::CART_SESSION_KEY);

        $session->set(CartSessionProductService::CART_SESSION_KEY, $existing + $array);
        $session->save();
    }


}