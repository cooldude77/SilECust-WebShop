<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Header\OrderHeaderChangedEvent;
use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemEditEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalSnapShot;
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
            OrderHeaderChangedEvent::EVENT_NAME => ['afterOrderHeaderChanged', 100]
        ];

    }

    public function afterOrderHeaderChanged(OrderHeaderChangedEvent $event): void
    {
      //  $this->journalSnapShot->snapShot($event->getOrderHeader());
    }

}