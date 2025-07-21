<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Journal\Item;

use Silecust\WebShop\Event\Transaction\Order\Item\BeforeOrderItemChangedEvent;
use Silecust\WebShop\Service\Transaction\Order\Admin\Item\Changes\ChangedDataFinder;
use Silecust\WebShop\Service\Transaction\Order\Journal\OrderJournalRecorder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class BeforeOrderItemChanged implements EventSubscriberInterface
{
    public function __construct(
        private OrderJournalRecorder $orderJournalRecorder,
        private ChangedDataFinder    $changedOrderHeaderFinder,

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeOrderItemChangedEvent::EVENT_NAME => ['afterOrderItemChanged', 100]
        ];

    }

    public function afterOrderItemChanged(BeforeOrderItemChangedEvent $event): void
    {
        $changedData = $this->changedOrderHeaderFinder->getChangedData($event->getOrderItem(), $event->getRequestData());

        $this->orderJournalRecorder->recordChanges($event->getOrderItem()->getOrderHeader(), $changedData, $event->getRequestData()['changeNote']);
    }

}