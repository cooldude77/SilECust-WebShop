<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentSuccessEvent extends Event
{
    public const string AFTER_PAYMENT_SUCCESS = 'payment.post.success';


    public function __construct(private readonly OrderHeader $orderHeader, private readonly array $paymentSuccessArray) {
    }

    public function getPaymentSuccessArray(): array
    {
        return $this->paymentSuccessArray;
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }


}