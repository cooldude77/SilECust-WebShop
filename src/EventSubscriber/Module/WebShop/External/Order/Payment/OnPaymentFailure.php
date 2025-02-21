<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderJournal;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentFailure implements EventSubscriberInterface
{
    private OrderHeader $orderHeader;

    public function __construct(
        private readonly OrderSave            $orderSave,
        private readonly OrderJournalSnapShot $orderJournalSnapShot)
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentFailureEvent::AFTER_PAYMENT_FAILURE => ['afterPaymentFailure', 100]
        ];

    }

    public function afterPaymentFailure(PaymentFailureEvent $paymentEvent): void
    {

        $orderHeader = $paymentEvent->getOrderHeader();
        $this->orderSave->setOrderStatus($orderHeader, OrderStatusTypes::ORDER_PAYMENT_FAILED);

        $this->orderSave->savePayment($orderHeader, $paymentEvent->getPaymentFailureArray());
        $this->orderJournalSnapShot->snapShot($paymentEvent->getOrderHeader());

    }
}