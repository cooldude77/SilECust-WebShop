<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Payment;

use Silecust\WebShop\Service\Module\WebShop\External\Order\DTO\OrderObject;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;

class PaymentService
{
    public function __construct(private readonly OrderSave $webShopOrderService)
    {

    }

    public function createNewOrder(): OrderObject
    {

        return $this->webShopOrderService->createOrderObject();

    }
}