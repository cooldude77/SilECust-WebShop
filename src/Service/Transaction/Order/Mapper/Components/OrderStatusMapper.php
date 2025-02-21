<?php

namespace Silecust\WebShop\Service\Transaction\Order\Mapper\Components;

use Silecust\WebShop\Entity\OrderStatus;
use Silecust\WebShop\Repository\OrderStatusRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use Silecust\WebShop\Service\Transaction\Order\SnapShot\OrderSnapShotCreator;

/**
 *
 */
class OrderStatusMapper
{
    /**
     * @param OrderStatusRepository     $orderStatusRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param OrderSnapShotCreator      $orderSnapShotCreator
     */
    public function __construct(private readonly OrderStatusRepository $orderStatusRepository,
        private readonly OrderStatusTypeRepository $orderStatusTypeRepository,
        private readonly OrderSnapShotCreator $orderSnapShotCreator
    ) {
    }

    /**
     * @param        $orderHeader
     * @param string $orderStatusType
     * @param string $note
     *
     * @return OrderStatus
     */
    public function mapAndSetHeader($orderHeader, string $orderStatusType, string $note
    ): OrderStatus {

        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(
            ['type' => $orderStatusType]
        );
        $orderStatus = $this->orderStatusRepository->create($orderHeader, $orderStatusType);
        $orderStatus->setOrderStatusType($orderStatusType);

        $orderStatus->setDateOfStatusSet(new \DateTime());
        $orderStatus->setNote($note);

        // snapshot will be created after flush

        return $orderStatus;

    }

}