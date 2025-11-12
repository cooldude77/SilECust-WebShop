<?php

namespace Silecust\WebShop\Service\Transaction\Order\Mapper\Components;

use DateTime;
use Silecust\WebShop\Entity\OrderStatus;
use Silecust\WebShop\Repository\OrderStatusRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;

/**
 *
 */
class OrderStatusMapper
{
    /**
     * @param OrderStatusRepository $orderStatusRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     */
    public function __construct(private readonly OrderStatusRepository     $orderStatusRepository,
                                private readonly OrderStatusTypeRepository $orderStatusTypeRepository
    )
    {
    }

    /**
     * @param        $orderHeader
     * @param string $orderStatusType
     * @param string $note
     *
     * @return OrderStatus
     */
    public function mapAndSetHeader($orderHeader, string $orderStatusType, string $note
    ): OrderStatus
    {

        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(
            ['type' => $orderStatusType]
        );
        $orderStatus = $this->orderStatusRepository->create($orderHeader, $orderStatusType);
        $orderStatus->setOrderStatusType($orderStatusType);

        $orderStatus->setDateOfStatusSet(new DateTime());
        $orderStatus->setNote($note);

        // snapshot will be created after flush

        return $orderStatus;

    }

}