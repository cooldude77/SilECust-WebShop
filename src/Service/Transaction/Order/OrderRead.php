<?php

namespace App\Service\Transaction\Order;

use App\Entity\Customer;
use App\Entity\OrderHeader;
use App\Entity\OrderPayment;
use App\Repository\OrderAddressRepository;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderItemPriceBreakupRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderPaymentRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Repository\PriceProductBaseRepository;
use App\Service\Transaction\Order\Object\OrderObject;
use App\Service\Transaction\Order\Status\OrderStatusTypes;

/**
 *
 */
readonly class OrderRead
{
    /**
     * @param OrderHeaderRepository     $orderHeaderRepository
     * @param OrderItemRepository       $orderItemRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param OrderAddressRepository    $orderAddressRepository
     * @param OrderPaymentRepository    $orderPaymentRepository
     */
    public function __construct(private OrderHeaderRepository $orderHeaderRepository,
        private OrderItemRepository $orderItemRepository,
        private OrderStatusTypeRepository $orderStatusTypeRepository,
        private OrderAddressRepository $orderAddressRepository,
        private OrderPaymentRepository $orderPaymentRepository
    ) {
    }


    /**
     * @param Customer $customer
     *
     * @return bool
     */
    public function isOpenOrder(Customer $customer): bool
    {
        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(
            ['type' => OrderStatusTypes::ORDER_CREATED]
        );

        return $this->orderHeaderRepository->findOneBy(['customer' => $customer,
                                                        'orderStatusType' => $orderStatusType])
            != null;
    }

    /**
     * @param Customer $customer
     *
     * @return OrderHeader|null
     */
    public function getOpenOrder(Customer $customer): ?OrderHeader
    {
        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(
            ['type' => OrderStatusTypes::ORDER_CREATED]
        );

        return $this->orderHeaderRepository->findOneBy(['customer' => $customer,
                                                        'orderStatusType' => $orderStatusType]);
    }

    /**
     * @param OrderHeader $orderHeader
     *
     * @return OrderObject
     */
    public function getOrderObject(OrderHeader $orderHeader): OrderObject
    {
        $object = new OrderObject();
        $object->setOrderHeader($orderHeader);
        $object->setOrderAddress($this->getAddresses($orderHeader));
        $object->setOrderItems($this->getOrderItems($orderHeader));
        $object->setOrderPayment($this->getPayment($orderHeader));

        return $object;
    }

    /**
     * @param OrderHeader|null $orderHeader
     *
     * @return array
     */
    public function getAddresses(?OrderHeader $orderHeader): array
    {
        return $this->orderAddressRepository->findBy(['orderHeader' => $orderHeader]);
    }

    /**
     * @param OrderHeader $orderHeader
     *
     * @return array
     */
    public function getOrderItems(OrderHeader $orderHeader): array
    {

        return $this->orderItemRepository->findBy(['orderHeader' => $orderHeader]);

    }


    /**
     * @param OrderHeader $orderHeader
     *
     * @return OrderPayment|null
     *
     * If the order is open or payment not completed then return value
     * can be null
     */
    public function getPayment(OrderHeader $orderHeader): OrderPayment|null
    {
        return $this->orderPaymentRepository->findOneBy(['orderHeader' => $orderHeader]);
    }

}