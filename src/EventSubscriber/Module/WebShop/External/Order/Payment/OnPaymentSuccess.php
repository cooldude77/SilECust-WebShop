<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Event\Module\WebShop\External\Payment\PaymentEvent;
use App\Event\Module\WebShop\External\Payment\Types\PaymentEventTypes;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(private OrderRead $orderRead,
        private OrderSave $orderSave
    ) {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEventTypes::AFTER_PAYMENT_SUCCESS => 'afterPaymentSuccess'
        ];

    }

    public function afterPaymentSuccess(PaymentEvent $paymentEvent): void
    {

      $orderHeader =   $this->orderRead->getOpenOrder($paymentEvent->getCustomer());

      $this->orderSave->setOrderStatus($orderHeader,OrderStatusTypes::ORDER_PAYMENT_COMPLETE);


    }
}