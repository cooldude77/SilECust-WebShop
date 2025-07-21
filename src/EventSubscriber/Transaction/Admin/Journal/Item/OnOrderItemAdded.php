<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal\Item;

use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemAddEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnOrderItemAdded implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder $journalSnapShot
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderItemAddEvent::EVENT_NAME => ['afterOrderItemAdded', 100]
        ];

    }

    public function afterOrderItemAdded(OrderItemAddEvent $event): void
    {
 //       $this->journalSnapShot->snapShot($event->getOrderItem()->getOrderHeader());
    }

}