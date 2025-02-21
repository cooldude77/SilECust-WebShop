<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Payment;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(private readonly OrderSave            $orderSave,
                                private readonly OrderJournalSnapShot $journalSnapShot)
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentSuccessEvent::AFTER_PAYMENT_SUCCESS => ['afterPaymentSuccess', 100]
        ];

    }

    public function afterPaymentSuccess(PaymentSuccessEvent $paymentEvent): void
    {

        $this->orderSave->setOrderStatus($paymentEvent->getOrderHeader(), OrderStatusTypes::ORDER_PAYMENT_COMPLETE);

        $this->orderSave->savePayment($paymentEvent->getOrderHeader(), $paymentEvent->getPaymentSuccessArray());
        $this->journalSnapShot->snapShot($paymentEvent->getOrderHeader());

    }
}