<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Entity\Customer;
use App\Event\Module\WebShop\External\Payment\PaymentEvent;
use App\Event\Module\WebShop\External\Payment\Types\PaymentEventTypes;
use App\Service\Module\WebShop\External\Order\OrderRead;
use App\Service\Module\WebShop\External\Order\OrderSave;
use App\Service\Module\WebShop\External\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentFailure implements EventSubscriberInterface
{
    public function __construct(private OrderRead $orderRead,
        private OrderSave $orderSave
    ) {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEventTypes::AFTER_PAYMENT_FAILURE => 'afterPaymentFailure'
        ];

    }

    public function afterPaymentFailure(PaymentEvent $paymentEvent,Customer $customer): void
    {

      $orderHeader =   $this->orderRead->getOpenOrder($customer);

      $this->orderSave->setOrderStatus($orderHeader,OrderStatusTypes::ORDER_PAYMENT_FAILED);


    }
}