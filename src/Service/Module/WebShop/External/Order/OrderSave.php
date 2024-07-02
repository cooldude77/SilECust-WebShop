<?php

namespace App\Service\Module\WebShop\External\Order;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\OrderAddress;
use App\Entity\OrderHeader;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderAddressRepository;
use App\Repository\OrderStatusTypeRepository;
use App\Service\Component\Database\DatabaseOperations;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use App\Service\Module\WebShop\External\Order\Mapper\Components\OrderAddressMapper;
use App\Service\Module\WebShop\External\Order\Mapper\Components\OrderHeaderMapper;
use App\Service\Module\WebShop\External\Order\Mapper\Components\OrderItemMapper;
use App\Service\Module\WebShop\External\Order\Mapper\Components\OrderStatusMapper;
use App\Service\Module\WebShop\External\Order\Status\OrderStatusTypes;

/**
 *
 */
readonly class OrderSave
{

    /**
     * @param OrderHeaderMapper  $orderHeaderMapper
     * @param OrderItemMapper    $orderItemMapper
     * @param OrderAddressMapper $orderAddressMapper
     * @param OrderStatusMapper  $orderStatusMapper
     * @param DatabaseOperations $databaseOperations
     */
    public function __construct(private OrderHeaderMapper $orderHeaderMapper,
        private OrderItemMapper $orderItemMapper,
        private OrderAddressMapper $orderAddressMapper,
        private OrderStatusMapper $orderStatusMapper,
        private OrderAddressRepository $orderAddressRepository,
        private OrderStatusTypeRepository $orderStatusTypeRepository,
        private DatabaseOperations $databaseOperations
    ) {
    }


    /**
     * @return void
     */
    public function createNewOrderFromCart(Customer $customer): void
    {


        $orderHeader = $this->orderHeaderMapper->create($customer);


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

    /**
     * @return OrderHeader
     */
    public function mapAndPersist(): OrderHeader
    {
        $this->orderPreMapAndPersistChecks();

        $orderHeader = $this->orderHeaderMapper->create();

        $orderItems = $this->orderItemMapper->mapAndSetHeader($orderHeader);
        $orderAddresses = $this->orderAddressMapper->mapAndSetHeader($orderHeader);
        $orderStatus = $this->orderStatusMapper->mapAndSetHeader(
            $orderHeader, OrderStatusTypes::ORDER_CREATED, "note" //todo
        );

        // validate

        $this->databaseOperations->persist($orderHeader);

        foreach ($orderItems as $item) {
            $this->databaseOperations->persist($item);
        }


        $this->databaseOperations->persist($orderAddresses);
        $this->databaseOperations->persist($orderStatus);


        return $orderHeader;

    }

    /**
     * @return void
     */
    private function orderPreMapAndPersistChecks()
    {

        // todo
    }

    public function updateOrderAddItem(OrderItem $item): void
    {
        // todo: check validity?

        $this->databaseOperations->persist($item);
        $this->databaseOperations->flush();
    }

    public function updateOrderItemsFromCartArray(array $cartArray, array $orderItems): void
    {

        // todo: check count same

        /**
         * @var   int              $key
         * @var  CartSessionObject $cartObject
         */
        foreach ($cartArray as $key => $cartObject) /** @var OrderItem $item */ {
            foreach ($orderItems as $item) {
                if ($item->getProduct()->getId() == $key) {
                    $item->setQuantity($cartObject->quantity);

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
}