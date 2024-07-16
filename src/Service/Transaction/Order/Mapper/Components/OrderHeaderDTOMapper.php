<?php

namespace App\Service\Transaction\Order\Mapper\Components;

use App\Entity\OrderHeader;
use App\Entity\OrderStatusType;
use App\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use App\Repository\CustomerRepository;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderStatusTypeRepository;

class OrderHeaderDTOMapper
{
    public function __construct(private readonly OrderHeaderRepository $orderHeaderRepository,
        private readonly OrderStatusTypeRepository $orderStatusTypeRepository,
        private readonly CustomerRepository $customerRepository
    ) {
    }

    public function mapToEntityForCreate(OrderHeaderDTO $orderHeaderDTO): \App\Entity\OrderHeader
    {
        $customer = $this->customerRepository->findOneBy(['id' => $orderHeaderDTO->customerId]);

        return $this->orderHeaderRepository->create($customer);

    }

    /**
     * @param OrderHeaderDTO $orderHeaderDTO
     *
     * @return OrderHeader
     */
    public function mapDtoToEntityForEdit(OrderHeaderDTO $orderHeaderDTO): OrderHeader
    {
        /** @var OrderHeader $orderHeader */
        $orderHeader = $this->orderHeaderRepository->find($orderHeaderDTO->id);

        /** @var OrderStatusType $statusType */
        $statusType = $this->orderStatusTypeRepository->find($orderHeaderDTO->orderStatusTypeId);
        $orderHeader->setOrderStatusType($statusType);

         return $orderHeader;
    }


}