<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Header\OrderHeaderChangedEvent;
use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemEditEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnOrderItemEdit implements EventSubscriberInterface
{
    public function __construct(
        private readonly OrderJournalSnapShot $journalSnapShot

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderHeaderChangedEvent::ORDER_HEADER_CHANGED => ['afterOrderChanged', 100]
        ];

    }

    public function afterOrderChanged(OrderItemEditEvent $event): void
    {
        $this->journalSnapShot->snapShot($event->getOrderItem()->getOrderHeader());
    }

}