<?php

namespace Silecust\WebShop\Service\Transaction\Order\Journal;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Repository\OrderJournalRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Symfony\Component\Serializer\SerializerInterface;

class OrderJournalSnapShot
{
    public function __construct(
        private readonly OrderJournalRepository $orderJournalRepository,
        private readonly OrderRead              $orderRead,
        private readonly SerializerInterface    $serializer,
        private readonly DatabaseOperations     $databaseOperations)
    {
    }

    /**
     * @param OrderHeader $orderHeader
     * @return void
     */
    public function snapShot(OrderHeader $orderHeader): void
    {

        $orderJournal = $this->orderJournalRepository->create($orderHeader);

        $orderObject = $this->orderRead->getOrderObject($orderHeader);

        $snapShot = $this->serializer->serialize($orderObject, 'json');

        $arrayFromSnapShot =  json_decode($snapShot, true);

        // todo: How to stop update for existing table
        // ie to never allow snapshot table to be updated
        $orderJournal->setOrderSnapShot($arrayFromSnapShot);

        $this->databaseOperations->save($orderJournal);
    }
}