<?php

namespace App\Event\Module\WebShop\External\Payment;

use App\Entity\OrderHeader;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentStartEvent extends Event
{
    public const string BEFORE_PAYMENT_PROCESS = 'payment.pre.payment';


    public function __construct(private readonly OrderHeader $orderHeader) {
    }

    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }


}