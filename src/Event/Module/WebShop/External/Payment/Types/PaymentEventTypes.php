<?php

namespace App\Event\Module\WebShop\External\Payment\Types;

class PaymentEventTypes
{
    public const string BEFORE_PAYMENT_PROCESS = 'payment.pre.payment';
    public const string AFTER_PAYMENT_SUCCESS = 'payment.post.success';
    public const string AFTER_PAYMENT_FAILURE = 'payment.post.failure';

}