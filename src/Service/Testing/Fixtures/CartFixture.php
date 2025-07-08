<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Item\CartItem;
use Symfony\Component\HttpFoundation\Session\Session;

trait CartFixture
{
    private function createSessionKey(Session $session): void
    {
        $session->set(CartProductManager::CART_SESSION_KEY, []);
        $session->save();
    }

    private function addProductToCart(Session $session, Product $product, int $quantity): void
    {
        $array = [$product->getId() => new CartItem($product->getId(), 4)];

        $existing = $session->get(CartProductManager::CART_SESSION_KEY);

        $session->set(CartProductManager::CART_SESSION_KEY, $existing + $array);
        $session->save();
    }


}