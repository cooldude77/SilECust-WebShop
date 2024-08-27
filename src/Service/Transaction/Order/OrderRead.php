<?php

namespace App\Service\Transaction\Order;

use App\Entity\Customer;
use App\Entity\OrderHeader;
use App\Entity\OrderPayment;
use App\Exception\Module\WebShop\External\CheckOut\ShippingAddressNotSetException;
use App\Repository\OrderAddressRepository;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderItemPaymentPriceRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderPaymentRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Service\Transaction\Order\Object\OrderObject;
use App\Service\Transaction\Order\Status\OrderStatusTypes;

/**
 *
 */
readonly class OrderRead
{
    /**
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param OrderItemRepository $orderItemRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param OrderAddressRepository $orderAddressRepository
     * @param OrderPaymentRepository $orderPaymentRepository
     */
    public function __construct(private OrderHeaderRepository           $orderHeaderRepository,
                                private OrderItemRepository             $orderItemRepository,
                                private OrderStatusTypeRepository       $orderStatusTypeRepository,
                                private OrderAddressRepository          $orderAddressRepository,
                                private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
                                private OrderPaymentRepository          $orderPaymentRepository
    )
    {
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
        $object->setOrderItemPaymentPrices($this->getOrderItemPaymentPrices($orderHeader));

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
     * @return OrderPayment|null
     *
     * If the order is open or payment not completed then return value
     * can be null
     */
    public function getPayment(OrderHeader $orderHeader): OrderPayment|null
    {
        return $this->orderPaymentRepository->findOneBy(['orderHeader' => $orderHeader]);
    }

    public function getOrderItem(OrderHeader $orderHeader, ?\App\Entity\Product $product
    ): ?\App\Entity\OrderItem
    {
        return $this->orderItemRepository->findOneBy([
            'orderHeader' => $orderHeader,
            'product' => $product]);
    }


    public function getShippingAddress($orderHeader): \App\Entity\OrderAddress
    {

        $address = $this->orderAddressRepository->findOneBy([
            'orderHeader' => $orderHeader,
            'addressType' => 'shipping'
        ]);

        if ($address == null) {
            throw new ShippingAddressNotSetException();
        }

        return $address;

    }

    public function getOrderItems(OrderHeader $orderHeader): array
    {
        return $this->orderItemRepository->findBy(['orderHeader' => $orderHeader]);
    }

    public function getOrderItemPaymentPrices(OrderHeader $orderHeader): array
    {
        return $this->orderItemPaymentPriceRepository->findByOrderHeader($orderHeader);
    }

    public function getOrder(int $id): OrderHeader
    {
        return $this->orderHeaderRepository->find($id);
    }

    public function getOrderByGeneratedId(string $generatedId): OrderHeader
    {
        return $this->orderHeaderRepository->findOneBy(['generatedId' => $generatedId]);
    }

}