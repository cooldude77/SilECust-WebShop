<?php

namespace Silecust\WebShop\Service\Transaction\Order\Journal;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Repository\OrderJournalRepository;
use Silecust\WebShop\Service\Transaction\Order\Admin\Header\Changes\ChangedOrderHeaderFinder;

readonly class OrderJournalRecorder
{
    public function __construct(
        private OrderJournalRepository   $orderJournalRepository,
         private EntityManagerInterface   $entityManager)
    {
    }


    public function recordHeaderChanges(OrderHeader $orderHeader, array $requestData): void
    {

        // Checks for the status changes
        $orderJournal = $this->orderJournalRepository->create($orderHeader);

        $orderJournal->setOrderSnapShot($this->changedOrderHeaderFinder->getChangedData($orderHeader, $requestData));

        $orderJournal->setChangeNote($requestData['changeNote']);

        $this->entityManager->persist($orderJournal);
    }

    public function recordChanges(OrderHeader $orderHeader, $changesArray, $changeNote): void
    {
        // Checks for the status changes
        $orderJournal = $this->orderJournalRepository->create($orderHeader);

        $orderJournal->setOrderSnapShot($changesArray);

        $orderJournal->setChangeNote($changeNote);

        $this->entityManager->persist($orderJournal);


    }

}