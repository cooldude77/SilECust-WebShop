<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Payment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentFailureEvent extends Event
{

    public const string AFTER_PAYMENT_FAILURE = 'payment.post.failure';

    public function __construct(private readonly Request $request)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }


}