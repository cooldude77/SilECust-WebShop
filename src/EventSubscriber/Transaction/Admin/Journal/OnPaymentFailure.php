<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentFailureResponseResolverInterface;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalChangesType;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentFailure implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder                    $orderJournalRecorder,
        private PaymentFailureResponseResolverInterface $paymentFailureResponseResolver,

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentFailureEvent::EVENT_NAME => ['afterPaymentFailure', 0]
        ];

    }

    public function afterPaymentFailure(PaymentFailureEvent $event): void
    {
        $info = $this->paymentFailureResponseResolver->resolve($event->getRequest());
        $changes = [
            OrderJournalChangesType::PAYMENT_FAILURE => [
                OrderJournalChangesType::OLD_VALUE => '',
                OrderJournalChangesType::NEW_VALUE => $info
            ]
        ];
        $this->orderJournalRecorder->recordChanges($event->getOrderHeader(), $changes, "Payment Failure");
    }

}