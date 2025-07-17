<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemEditEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnOrderItemEdited implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder $journalSnapShot

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderItemEditEvent::EVENT_NAME => ['afterOrderChanged', 100]
        ];

    }

    public function afterOrderChanged(OrderItemEditEvent $event): void
    {
     //   $this->journalSnapShot->snapShot($event->getOrderItem()->getOrderHeader());
    }

}