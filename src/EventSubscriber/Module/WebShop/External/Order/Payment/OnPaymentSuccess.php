<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use App\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use App\Service\Transaction\Order\OrderSave;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
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