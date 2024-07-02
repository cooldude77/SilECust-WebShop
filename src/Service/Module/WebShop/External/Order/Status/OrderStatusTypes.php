<?php

namespace App\Service\Module\WebShop\External\Order\Status;

readonly class OrderStatusTypes
{
    public const ORDER_CREATED = "ORDER_CREATED";
    public const ORDER_PAYMENT_COMPLETE ="ORDER_PAYMENT_COMPLETE";
    public const ORDER_IN_PROCESS ="ORDER_IN_PROCESS";
    public const ORDER_SHIPPED ="ORDER_SHIPPED";
    public const ORDER_COMPLETED ="ORDER_COMPLETED";
    const ORDER_PAYMENT_FAILED = 'ORDER_PAYMENT_FAILED';


}