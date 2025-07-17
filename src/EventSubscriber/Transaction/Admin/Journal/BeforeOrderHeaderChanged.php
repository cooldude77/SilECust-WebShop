<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal;

use Silecust\WebShop\Event\Transaction\Order\Header\BeforeOrderHeaderChangedEvent;
use Silecust\WebShop\Service\Transaction\Order\Admin\Header\Changes\ChangedOrderHeaderFinder;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class BeforeOrderHeaderChanged implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder     $orderJournalRecorder,
        private ChangedOrderHeaderFinder $changedOrderHeaderFinder,

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
        $changedData = $this->changedOrderHeaderFinder->getChangedData($event->getOrderHeader(), $event->getRequestData());

        $this->orderJournalRecorder->recordChanges($event->getOrderHeader(), $changedData, $event->getRequestData()['changeNote']);
    }

}