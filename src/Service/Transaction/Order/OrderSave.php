<?php

namespace Silecust\WebShop\Service\Transaction\Order;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Entity\OrderAddress;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Entity\OrderItemPaymentPrice;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Repository\OrderAddressRepository;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;
use Silecust\WebShop\Repository\OrderItemRepository;
use Silecust\WebShop\Repository\OrderPaymentRepository;
use Silecust\WebShop\Repository\OrderShippingRepository;
use Silecust\WebShop\Repository\OrderStatusRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Silecust\WebShop\Service\Transaction\Order\IdGeneration\OrderIdStrategyInterface;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;

/**
 *
 */
readonly class OrderSave
{

    /**
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param OrderItemRepository $orderItemRepository
     * @param OrderAddressRepository $orderAddressRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param DatabaseOperations $databaseOperations
     */
    public function __construct(
        private readonly OrderHeaderRepository           $orderHeaderRepository,
        private readonly OrderItemRepository             $orderItemRepository,
        private readonly OrderAddressRepository          $orderAddressRepository,
        private readonly OrderStatusRepository           $orderStatusRepository,
        private readonly OrderStatusTypeRepository       $orderStatusTypeRepository,
        private readonly OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
        private readonly OrderIdStrategyInterface        $orderIdStrategy,
        private readonly OrderPaymentRepository          $orderPaymentRepository,
        private readonly OrderShippingRepository         $orderShippingRepository,
        private readonly PriceByCountryCalculator        $priceByCountryCalculator,
        private readonly DatabaseOperations              $databaseOperations,
    )
    {
    }


    /**
     * An order is implicitly created when cart is started
     * @param Customer $customer
     * @return void
     */
    public function createNewOrderFromCart(Customer $customer): void
    {


        $orderHeader = $this->orderHeaderRepository->create($customer);
        $orderHeader->setGeneratedId($this->orderIdStrategy->generateOrderId());

        $type = $this->orderStatusTypeRepository->findOneBy(['type' => OrderStatusTypes::ORDER_CREATED]);

        $orderStatus = $this->orderStatusRepository->create($orderHeader, $type);
        $orderStatus->setNote("Order Created");
        $this->databaseOperations->persist($orderStatus);

        $this->databaseOperations->persist($orderHeader);
        $this->databaseOperations->flush();

    }


    public function updateOrderItemsFromCartArray(array $cartArray, array $orderItems): void
    {

        // todo: check count same

        /**
         * @var   int $key
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

    public function createOrUpdateAddress(?OrderHeader    $orderHeader,
                                          CustomerAddress $address,
                                          array           $currentAddressesForOrder
    ): void
    {
        // no list was sent
        if (count($currentAddressesForOrder) == 0) {
            $orderAddress = $this->orderAddressRepository->create($orderHeader, $address);
            $this->databaseOperations->persist($orderAddress);
        } else {
            /** @var OrderAddress $orderAddress */
            foreach ($currentAddressesForOrder as $orderAddress) {
                if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {
                    $orderAddress->setShippingAddress($address);
                    $this->databaseOperations->persist($orderAddress);
                    break;
                } elseif ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING) {
                    $orderAddress->setBillingAddress($address);
                    $this->databaseOperations->persist($orderAddress);
                    break;
                }
            }
        }


        $this->databaseOperations->flush();

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

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function addNewItem(Product $product, int $quantity, OrderHeader $orderHeader): void
    {
        // todo: check if the item already exists
        $orderItem = $this->orderItemRepository->create($orderHeader, $product, $quantity);

        $priceObject = $this->priceByCountryCalculator->getPriceObject($orderItem);
        $itemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);

        $this->databaseOperations->save($itemPaymentPrice);

    }


    public function savePrice(OrderItem $orderItem, PriceObject $priceObject): void
    {

        /** @var OrderItemPaymentPrice $orderItemPaymentPrice */
        $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->findOneBy(['orderItem' => $orderItem]);
        if ($orderItemPaymentPrice == null)
            $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);
        else {
            $orderItemPaymentPrice->setBasePrice($priceObject->getBasePrice());
            $orderItemPaymentPrice->setDiscount($priceObject->getDiscount());
            $orderItemPaymentPrice->setTaxRate($priceObject->getTaxRate());
        }

        $this->databaseOperations->save($orderItemPaymentPrice);
    }

    public function savePayment(OrderHeader $orderHeader, string $paymentInformation): void
    {
        $orderPayment = $this->orderPaymentRepository->create($orderHeader, $paymentInformation);
        $this->databaseOperations->save($orderPayment);

    }

    public function saveShippingData(OrderHeader $orderHeader, array $data, OrderShipping $orderShipping = null): void
    {
        if ($orderShipping == null)
            $orderShipping = $this->orderShippingRepository->create(
                $orderHeader, $data['name'], $data['value'], $data['data']);
        else {
            $orderShipping->setValue($data['value'], $data['data']);
        }

        $this->databaseOperations->persist($orderShipping);

        $this->databaseOperations->flush();


    }

}