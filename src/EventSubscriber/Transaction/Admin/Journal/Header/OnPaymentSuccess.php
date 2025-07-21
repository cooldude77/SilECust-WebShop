<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal\Header;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentSuccessResponseResolverInterface;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalChangesType;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder                    $orderJournalRecorder,
        private PaymentSuccessResponseResolverInterface $paymentSuccessResponseResolver,

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentSuccessEvent::EVENT_NAME => ['afterPaymentSuccess', 0]
        ];

    }

    public function afterPaymentSuccess(PaymentSuccessEvent $event): void
    {
        $info = $this->paymentSuccessResponseResolver->resolve($event->getRequest());
        $changes = [
            OrderJournalChangesType::PAYMENT_SUCCESS => [
                OrderJournalChangesType::OLD_VALUE => '',
                OrderJournalChangesType::NEW_VALUE => $info
            ]
        ];
        $this->orderJournalRecorder->recordChanges($event->getOrderHeader(), $changes, "Payment Success");
    }

}