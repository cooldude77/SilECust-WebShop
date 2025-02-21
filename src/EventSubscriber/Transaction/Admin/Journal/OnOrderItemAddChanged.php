<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemAddEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalSnapShot;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnOrderItemAddChanged implements EventSubscriberInterface
{
    public function __construct(
        private readonly OrderJournalSnapShot $journalSnapShot
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderItemAddEvent::ORDER_ITEM_ADDED => ['afterOrderItemAdded', 100]
        ];

    }

    public function afterOrderItemAdded(OrderItemAddEvent $event): void
    {
        $this->journalSnapShot->snapShot($event->getOrderItem()->getOrderHeader());
    }

}