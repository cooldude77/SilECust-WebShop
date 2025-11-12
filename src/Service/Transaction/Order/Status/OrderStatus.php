<?php

namespace Silecust\WebShop\Service\Transaction\Order\Status;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Repository\OrderStatusRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use function PHPUnit\Framework\assertNotNull;

readonly class OrderStatus
{

    public function __construct(private EntityManagerInterface    $entityManager,
                                private OrderStatusRepository     $orderStatusRepository,
                                private OrderStatusTypeRepository $orderStatusTypeRepository)
    {

        // Note: For each status change, a new row is created in status
    }

    public function onOrderCreate(OrderHeader $orderHeader, $note = "Order Created"): void
    {

        $type = $this->orderStatusTypeRepository->findOneBy(['type' => OrderStatusTypes::ORDER_CREATED]);
        assertNotNull($type);
        // check valid status to perform this operation
        // todo

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote($note);

        $orderHeader->setOrderStatusType($type);

        $this->entityManager->persist($orderStatus);

    }

    public function setOrderPaymentSuccess(OrderHeader $orderHeader, $note = "Payment Completed"): void
    {
        // check valid status to perform this operation
        // todo

        $type = $this->orderStatusTypeRepository->findOneBy(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        assertNotNull($type);

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote($note);

        $orderHeader->setOrderStatusType($type);

        $this->entityManager->persist($orderStatus);

    }

    public function setOrderPaymentFailed(OrderHeader $orderHeader, $note = "Payment Failed"): void
    {
        // check valid status to perform this operation
        // todo
        $type = $this->orderStatusTypeRepository->findOneBy(['type' => OrderStatusTypes::ORDER_PAYMENT_FAILED]);
        assertNotNull($type);

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote($note);

        $orderHeader->setOrderStatusType($type);

        $this->entityManager->persist($orderStatus);


    }

    public function setOrderToInProcess(OrderHeader $orderHeader, $note = "Completed"): void
    {

        // check valid status to perform this operation
        // todo

        $type = $this->orderStatusRepository->findOneBy(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        assertNotNull($type);

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote($note);

        $orderHeader->setOrderStatusType($type);

        $this->entityManager->persist($orderStatus);


    }

    public function setOrderToCompleted(OrderHeader $orderHeader, $note = "Order Completed"): void
    {

        // check valid status to perform this operation
        // todo

        $type = $this->orderStatusTypeRepository->findOneBy(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        assertNotNull($type);

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote($note);

        $orderHeader->setOrderStatusType($type);

        $this->entityManager->persist($orderStatus);

    }

}