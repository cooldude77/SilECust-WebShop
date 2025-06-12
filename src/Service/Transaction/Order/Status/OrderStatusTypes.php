<?php

namespace Silecust\WebShop\Service\Transaction\Order\Status;

readonly class OrderStatusTypes
{
    public const string ORDER_CREATED = "ORDER_CREATED";
    public const string ORDER_PAYMENT_COMPLETE = "ORDER_PAYMENT_COMPLETE";
    public const string ORDER_IN_PROCESS = "ORDER_IN_PROCESS";
    public const string ORDER_SHIPPED = "ORDER_SHIPPED";
    public const string ORDER_COMPLETED = "ORDER_COMPLETED";
    public const string ORDER_PAYMENT_FAILED = 'ORDER_PAYMENT_FAILED';


}