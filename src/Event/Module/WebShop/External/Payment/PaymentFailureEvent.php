<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentFailureEvent extends Event
{

    public const string AFTER_PAYMENT_FAILURE = 'payment.post.failure';

    public function __construct(private readonly OrderHeader $orderHeader,private readonly array $paymentFailureArray) {
    }

    public function getPaymentFailureArray(): array
    {
        return $this->paymentFailureArray;
    }

    public function getOrderHeader():OrderHeader
    {
        return  $this->orderHeader;
    }


}