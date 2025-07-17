<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Header\BeforeOrderHeaderChangedEvent;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class BeforeOrderHeaderChanged implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder $orderJournalRecorder
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeOrderHeaderChangedEvent::EVENT_NAME => ['afterOrderHeaderChanged', 100]
        ];

    }

    public function afterOrderHeaderChanged(BeforeOrderHeaderChangedEvent $event): void
    {
        $this->orderJournalRecorder->recordHeaderChanges($event->getOrderHeader(), $event->getRequestData());
    }

}