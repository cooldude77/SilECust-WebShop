<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentValidationEvent extends Event
{
    public const string ON_PAYMENT_VALIDATION = 'payment.on.validation';


    public function __construct(private readonly OrderHeader $orderHeader, private readonly Request $request)
    {
    }


    public function getOrderHeader(): OrderHeader
    {
        return $this->orderHeader;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }


}