<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\CheckOut;

use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;

readonly class CheckOutService
{


    public function __construct(private CartProductManager $cartService
    ) {
    }

    public function isEverythingOkay(): bool
    {
        // todo:

        return !empty($this->cartService->getCartArray());
    }


}