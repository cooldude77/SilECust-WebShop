<?php

namespace App\EventSubscriber\Transaction\Admin\Journal;

use App\Event\Transaction\Order\Admin\Header\OrderHeaderChangedEvent;
use App\Event\Transaction\Order\Admin\Item\OrderItemEditEvent;
use App\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnOrderHeaderChanged implements EventSubscriberInterface
{
    public function __construct(
        private readonly OrderJournalSnapShot $journalSnapShot
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderItemEditEvent::ORDER_ITEM_EDITED => ['afterOrderItemChanged', 100]
        ];

    }

    public function afterOrderItemChanged(OrderHeaderChangedEvent $event): void
    {
        $this->journalSnapShot->snapShot($event->getOrderHeader());
    }

}