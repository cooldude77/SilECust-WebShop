<?php

namespace App\EventSubscriber\Transaction\Admin\Journal;

use App\Event\Transaction\Order\Admin\Header\OrderHeaderChangedEvent;
use App\Event\Transaction\Order\Admin\Item\OrderItemEditEvent;
use App\Service\Transaction\Order\Journal\OrderJournalSnapShot;
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