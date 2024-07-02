<?php

namespace App\Service\Module\WebShop\External\Order\Mapper\Components;

use App\Entity\Customer;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Service\Module\WebShop\External\Order\Status\OrderStatusTypes;

class OrderHeaderMapper
{
    public function __construct(private readonly OrderHeaderRepository $orderHeaderRepository,
        private readonly OrderStatusTypeRepository $orderStatusTypeRepository
    ) {
    }

    public function create(Customer $customer): \App\Entity\OrderHeader
    {

        $orderHeader = $this->orderHeaderRepository->create($customer);

        $orderHeader->setDateTimeOfOrder(new \DateTime());

        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(
            ['type' => OrderStatusTypes::ORDER_CREATED]
        );

        $orderHeader->setOrderStatusType($orderStatusType);

        return $orderHeader;

    }
}