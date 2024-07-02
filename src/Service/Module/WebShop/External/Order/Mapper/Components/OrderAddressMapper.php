<?php

namespace App\Service\Module\WebShop\External\Order\Mapper\Components;

use App\Entity\OrderAddress;
use App\Entity\OrderHeader;
use App\Repository\OrderAddressRepository;
use App\Service\Module\WebShop\External\Address\CheckOutAddressQuery;

readonly class OrderAddressMapper
{
    public function __construct(private CheckOutAddressQuery $checkOutAddressQuery,
        private OrderAddressRepository $orderAddressRepository
    ) {
    }

    public function mapAndSetHeader(OrderHeader $orderHeader): OrderAddress
    {

        $orderAddress = $this->orderAddressRepository->create($orderHeader);

        $orderAddress->setBillingAddress( $this->checkOutAddressQuery->getBillingAddress());
        $orderAddress->setShippingAddress($this->checkOutAddressQuery->getShippingAddress());

        return $orderAddress;


    }
}