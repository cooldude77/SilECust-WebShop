<?php

namespace App\Service\Module\WebShop\External\Order\Mapper\Components;

use App\Entity\OrderStatus;
use App\Repository\OrderStatusRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Service\Module\WebShop\External\Order\SnapShot\OrderSnapShotCreator;

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