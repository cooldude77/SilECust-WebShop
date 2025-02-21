<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\CheckOut;

use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;

class CheckOutService
{


    public function __construct(private readonly CartSessionProductService $cartService
    ) {
    }

    public function isEverythingOkay(): bool
    {
        $ok = !empty($this->cartService->getCartArray());

        // todo:

        return $ok;
    }


}