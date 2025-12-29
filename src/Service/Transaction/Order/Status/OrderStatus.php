<?php

namespace Silecust\WebShop\Service\Transaction\Order\Status;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Repository\OrderStatusRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

/**
 *
 */
readonly class OrderStatus
{

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Silecust\WebShop\Repository\OrderStatusRepository $orderStatusRepository
     * @param \Silecust\WebShop\Repository\OrderStatusTypeRepository $orderStatusTypeRepository
     */
    public function __construct(private EntityManagerInterface    $entityManager,
                                private OrderStatusRepository     $orderStatusRepository,
                                private OrderStatusTypeRepository $orderStatusTypeRepository)
    {

        // Note: For each status change, a new row is created in status
    }

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $orderHeader
     * @param $note
     * @return void
     */
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

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $orderHeader
     * @param $note
     * @return void
     */
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

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $orderHeader
     * @param $note
     * @return void
     */
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

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $orderHeader
     * @param $note
     * @return void
     */
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

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $orderHeader
     * @param $note
     * @return void
     */
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

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $entity
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getOrderCreatedAtDate(OrderHeader $entity): \DateTimeImmutable

    {

        $date = $this->getOrderStatusByType($entity, OrderStatusTypes::ORDER_IN_PROCESS)
            ->getStatusCreatedAt();

        $timeZone = new \DateTimeZone($this->getOrderStatusByType($entity, OrderStatusTypes::ORDER_IN_PROCESS)->getStatusCreatedAtTimeZone());
        $date->setTimezone($timeZone);

        return \DateTimeImmutable::createFromInterface($date);

    }

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $entity
     * @return \Silecust\WebShop\Entity\OrderStatus
     */
    public function getOrderStatusByType(OrderHeader $entity, string $orderStatusType): \Silecust\WebShop\Entity\OrderStatus
    {
        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(['type' => $orderStatusType]);
        assertNotNull($orderStatusType);

        $orderStatus = $this->orderStatusRepository->findOneBy(['orderHeader' => $entity, 'orderStatusType' => $orderStatusType]);
        assertNotEmpty($orderStatus);
        return $orderStatus;
    }


}