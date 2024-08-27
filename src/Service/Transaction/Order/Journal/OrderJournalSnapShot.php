<?php

namespace App\Service\Transaction\Order\Journal;

use App\Entity\OrderHeader;
use App\Repository\OrderJournalRepository;
use App\Service\Component\Database\DatabaseOperations;
use App\Service\Transaction\Order\OrderRead;
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