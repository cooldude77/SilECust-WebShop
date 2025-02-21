<?php

namespace Silecust\WebShop\Service\Transaction\Order\Mapper\Components;

use Silecust\WebShop\Entity\OrderAddress;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Repository\OrderAddressRepository;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressQuery;

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