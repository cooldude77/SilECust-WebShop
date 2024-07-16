<?php

namespace App\Service\Transaction\Order\Mapper\Components;

use App\Form\Transaction\Admin\Order\Header\OrderHeaderDTO;
use App\Repository\CustomerRepository;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderStatusTypeRepository;

class OrderHeaderMapper
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


}