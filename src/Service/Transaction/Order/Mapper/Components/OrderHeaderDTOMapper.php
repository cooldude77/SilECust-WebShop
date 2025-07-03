<?php

namespace Silecust\WebShop\Service\Transaction\Order\Mapper\Components;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderStatusType;
use Silecust\WebShop\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;

class OrderHeaderDTOMapper
{
    public function __construct(private readonly OrderHeaderRepository $orderHeaderRepository,
        private readonly OrderStatusTypeRepository $orderStatusTypeRepository,
        private readonly CustomerRepository $customerRepository
    ) {
    }

    public function mapToEntityForCreate(OrderHeaderDTO $orderHeaderDTO): \Silecust\WebShop\Entity\OrderHeader
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

    public function mapEntityToDtoForEdit(OrderHeader $orderHeader): OrderHeaderDTO
    {
        $orderHeaderDTO = new OrderHeaderDTO();
        $orderHeaderDTO->id = $orderHeader->getId();
        $orderHeaderDTO->orderStatusTypeId = $orderHeader->getOrderStatusType()->getId();
        return $orderHeaderDTO;
    }


}