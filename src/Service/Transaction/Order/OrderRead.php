<?php

namespace Silecust\WebShop\Service\Transaction\Order;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\OrderAddress;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Entity\OrderPayment;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\Module\WebShop\External\CheckOut\ShippingAddressNotSetException;
use Silecust\WebShop\Exception\Module\WebShop\External\Shipping\ShippingRecordByKeyNotFound;
use Silecust\WebShop\Repository\OrderAddressRepository;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;
use Silecust\WebShop\Repository\OrderItemRepository;
use Silecust\WebShop\Repository\OrderPaymentRepository;
use Silecust\WebShop\Repository\OrderShippingRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use Silecust\WebShop\Service\Transaction\Order\Object\OrderObject;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;

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
     * @param OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository
     * @param OrderShippingRepository $orderShippingRepository
     * @param OrderPaymentRepository $orderPaymentRepository
     */
    public function __construct(private OrderHeaderRepository           $orderHeaderRepository,
                                private OrderItemRepository             $orderItemRepository,
                                private OrderStatusTypeRepository       $orderStatusTypeRepository,
                                private OrderAddressRepository          $orderAddressRepository,
                                private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
                                private OrderShippingRepository         $orderShippingRepository,
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
        $object->setOrderShipping($this->getShippingData($orderHeader));

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

    /**
     * @param OrderHeader $orderHeader
     * @param Product|null $product
     * @return OrderItem|null
     */
    public function getOrderItem(OrderHeader $orderHeader, ?Product $product): ?OrderItem
    {
        return $this->orderItemRepository->findOneBy([
            'orderHeader' => $orderHeader,
            'product' => $product]);
    }


    /**
     * @param $orderHeader
     * @return OrderAddress
     * @throws ShippingAddressNotSetException
     */
    public function getShippingAddress($orderHeader): OrderAddress
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

    /**
     * @param OrderHeader $orderHeader
     * @return array
     */
    public function getOrderItems(OrderHeader $orderHeader): array
    {
        return $this->orderItemRepository->findBy(['orderHeader' => $orderHeader]);
    }

    /**
     * @param OrderHeader $orderHeader
     * @return array
     */
    public function getOrderItemPaymentPrices(OrderHeader $orderHeader): array
    {
        return $this->orderItemPaymentPriceRepository->findByOrderHeader($orderHeader);
    }

    /**
     * @param int $id
     * @return OrderHeader
     */
    public function getOrder(int $id): OrderHeader
    {
        return $this->orderHeaderRepository->find($id);
    }

    /**
     * @param string $generatedId
     * @return OrderHeader
     */
    public function getOrderByGeneratedId(string $generatedId): OrderHeader
    {
        return $this->orderHeaderRepository->findOneBy(['generatedId' => $generatedId]);
    }

    /**
     * @param OrderHeader $orderHeader
     * @return array
     */
    public function getShippingData(OrderHeader $orderHeader): array
    {
        return $this->orderShippingRepository->findBy(['orderHeader' => $orderHeader]);

    }

    /**
     * @throws ShippingRecordByKeyNotFound
     */
    public function findShippingDataByKey(OrderHeader $orderHeader, string $name): OrderShipping
    {
        $shippingData = $this->getShippingData($orderHeader);

        /**
         * @var  OrderShipping $orderShipping
         */
        foreach ($shippingData as $orderShipping)
            if ($orderShipping->getName() == $name) {
                return $orderShipping;
            }

        throw  new ShippingRecordByKeyNotFound();

    }

    /**
     * @param OrderHeader $orderHeader
     * @param Product $product
     * @return bool
     */
    public function orderItemExists(OrderHeader $orderHeader, Product $product): bool
    {

        return $this->orderItemRepository->findOneBy(['orderHeader' => $orderHeader, 'product' => $product]) != null;
    }


}