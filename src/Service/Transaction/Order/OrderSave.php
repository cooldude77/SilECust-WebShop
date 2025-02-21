<?php

namespace Silecust\WebShop\Service\Transaction\Order;

use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Entity\OrderAddress;
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Repository\OrderAddressRepository;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Repository\OrderItemPaymentPriceRepository;
use Silecust\WebShop\Repository\OrderItemRepository;
use Silecust\WebShop\Repository\OrderPaymentRepository;
use Silecust\WebShop\Repository\OrderShippingRepository;
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Silecust\WebShop\Service\Transaction\Order\IdGeneration\OrderIdStrategyInterface;

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
        private OrderHeaderRepository           $orderHeaderRepository,
        private OrderItemRepository             $orderItemRepository,
        private OrderAddressRepository          $orderAddressRepository,
        private OrderStatusTypeRepository       $orderStatusTypeRepository,
        private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
        private OrderIdStrategyInterface        $orderIdStrategy,
        private OrderPaymentRepository          $orderPaymentRepository,
        private OrderShippingRepository         $orderShippingRepository,
        private PriceByCountryCalculator        $priceByCountryCalculator,
        private DatabaseOperations              $databaseOperations,
    )
    {
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

    public function createOrUpdate(?OrderHeader $orderHeader, CustomerAddress $address,
                                   array        $currentAddressesForOrder
    ): void
    {
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

        $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->findOneBy(['orderItem' => $orderItem]);
        if ($orderItemPaymentPrice == null)
            $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);
        else {
            $orderItemPaymentPrice->setBasePrice($priceObject->getBasePrice());
            $orderItemPaymentPrice->setDiscount($priceObject->getDiscount());
            $orderItemPaymentPrice->setRateOfTax($priceObject->getTaxRate());
        }

        $this->databaseOperations->save($orderItemPaymentPrice);
    }

    public function savePayment(OrderHeader $orderHeader, array $paymentInformation): void
    {
        $orderPayment = $this->orderPaymentRepository->create($orderHeader, $paymentInformation);
        $this->databaseOperations->save($orderPayment);

    }

    public function saveShippingData(OrderHeader $orderHeader, array $valueAndDataArray): void
    {

        foreach ($valueAndDataArray as $data) {

            $shipping = $this->orderShippingRepository->create(
                $orderHeader, $data['name'], $data['value'], $data['data']);
            $this->databaseOperations->persist($shipping);
        }

        $this->databaseOperations->flush();


    }

}