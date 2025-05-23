<?php

namespace Silecust\WebShop\Event\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentSuccessEvent extends Event
{
    public const string EVENT_NAME = 'payment.post.success';


    public function __construct(private readonly Request $request)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }


}