<?php

namespace App\Service\Transaction\Order;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\OrderAddress;
use App\Entity\OrderHeader;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderAddressRepository;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderItemPaymentPriceRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Service\Component\Database\DatabaseOperations;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use App\Service\Transaction\Order\IdGeneration\OrderIdStrategyInterface;

/**
 *
 */
readonly class OrderSave
{

    /**
     * @param OrderHeaderRepository     $orderHeaderRepository
     * @param OrderItemRepository       $orderItemRepository
     * @param OrderAddressRepository    $orderAddressRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param DatabaseOperations        $databaseOperations
     */
    public function __construct(
        private OrderHeaderRepository           $orderHeaderRepository,
        private OrderItemRepository             $orderItemRepository,
        private OrderAddressRepository          $orderAddressRepository,
        private OrderStatusTypeRepository       $orderStatusTypeRepository,
        private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
        private OrderIdStrategyInterface        $orderIdStrategy,
        private DatabaseOperations              $databaseOperations,
    ) {
    }


    /**
     * @return void
     */
    public function createNewOrderFromCart(Customer $customer): void
    {


        $orderHeader = $this->orderHeaderRepository->create($customer);
        $orderHeader->setGeneratedId($this->orderIdStrategy->generateOrderId());

        $this->databaseOperations->persist($orderHeader);
        $this->databaseOperations->flush();

    }

    /**
     * @param OrderHeader $orderHeader
     *
     * @return void
     */
    public function flush(OrderHeader $orderHeader): void
    {
        $this->databaseOperations->flush();


    }


    public function updateOrderItemsFromCartArray(array $cartArray, array $orderItems): void
    {

        // todo: check count same

        /**
         * @var   int              $key
         * @var  CartSessionObject $cartObject
         */
        foreach ($cartArray as $key => $cartObject) /** @var OrderItem $orderItem */ {
            foreach ($orderItems as $orderItem) {
                if ($orderItem->getProduct()->getId() == $key) {
                    $orderItem->setQuantity($cartObject->quantity);

                }
            }
        }
        $this->databaseOperations->flush();
        $this->databaseOperations->clear();


    }

    public function updateOrderRemoveItem(Product $product, array $orderItems): void
    {
        /** @var OrderItem $item */
        foreach ($orderItems as $item) {
            if ($item->getProduct()->getId() == $product->getId()) {
                $this->databaseOperations->remove($item);
            }

        }
        $this->databaseOperations->flush();
        $this->databaseOperations->clear();


    }

    public function removeAllItems(array $orderItems): void
    {
        /** @var OrderItem $item */
        foreach ($orderItems as $item) {
            $this->databaseOperations->remove($item);
        }
        $this->databaseOperations->flush();
        $this->databaseOperations->clear();


    }

    public function createOrUpdate(?OrderHeader $orderHeader, CustomerAddress $address,
        array $currentAddressesForOrder
    ): void {
        // no list was sent
        if (count($currentAddressesForOrder) == 0) {
            $orderAddress = $this->orderAddressRepository->create($orderHeader, $address);
            $this->databaseOperations->save($orderAddress);
        } else {
            /** @var OrderAddress $orderAddress */
            foreach ($currentAddressesForOrder as $orderAddress) {
                if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {
                    $orderAddress->setShippingAddress($address);
                    break;
                } elseif ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING) {
                    $orderAddress->setBillingAddress($address);
                    break;
                }

            }
            $this->databaseOperations->flush();
        }

    }

    public function setOrderStatus(OrderHeader $orderHeader, string $orderStatusTypeString): void
    {
        $orderStatusType = $this->orderStatusTypeRepository->findOneBy
        (
            ['type' => $orderStatusTypeString]
        );

        $orderHeader->setOrderStatusType($orderStatusType);

        $this->databaseOperations->flush();

    }

    public function addNewItem(Product $product, int $quantity, OrderHeader $orderHeader): void
    {
        // todo: check if the item already exists
        $item = $this->orderItemRepository->create($orderHeader, $product, $quantity);

        $this->databaseOperations->save($item);

    }


    public function savePrice(OrderItem $orderItem, PriceObject $priceObject): void
    {

        $price = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);
        $this->databaseOperations->save($price);
    }

}