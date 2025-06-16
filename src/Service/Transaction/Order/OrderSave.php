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
use Symfony\Component\Serializer\SerializerInterface;


/**
 *
 */
readonly class OrderSave
{

    /**
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param OrderItemRepository $orderItemRepository
     * @param OrderAddressRepository $orderAddressRepository
     * @param OrderStatusRepository $orderStatusRepository
     * @param OrderStatusTypeRepository $orderStatusTypeRepository
     * @param OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository
     * @param OrderIdStrategyInterface $orderIdStrategy
     * @param OrderPaymentRepository $orderPaymentRepository
     * @param OrderShippingRepository $orderShippingRepository
     * @param PriceByCountryCalculator $priceByCountryCalculator
     * @param DatabaseOperations $databaseOperations
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private OrderHeaderRepository           $orderHeaderRepository,
        private OrderItemRepository             $orderItemRepository,
        private OrderAddressRepository          $orderAddressRepository,
        private OrderStatusRepository           $orderStatusRepository,
        private OrderStatusTypeRepository       $orderStatusTypeRepository,
        private OrderItemPaymentPriceRepository $orderItemPaymentPriceRepository,
        private OrderIdStrategyInterface        $orderIdStrategy,
        private OrderPaymentRepository          $orderPaymentRepository,
        private OrderShippingRepository         $orderShippingRepository,
        private PriceByCountryCalculator        $priceByCountryCalculator,
        private DatabaseOperations              $databaseOperations,
        private SerializerInterface             $serializer,
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


    /**
     * @param array $cartArray
     * @param array $orderItems
     * @return void
     */
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

    /**
     * @param Product $product
     * @param array $orderItems
     * @return void
     */
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

    /**
     * @param array $orderItems
     * @return void
     */
    public function removeAllItems(array $orderItems): void
    {
        /** @var OrderItem $item */
        foreach ($orderItems as $item) {
            $this->databaseOperations->remove($item);
        }
        $this->databaseOperations->flush();
        $this->databaseOperations->clear();


    }

    /**
     * @param OrderHeader|null $orderHeader
     * @param CustomerAddress $address
     * @param array $currentAddressesForOrder
     * @return void
     */
    public function createOrUpdateAddress(?OrderHeader    $orderHeader,
                                          CustomerAddress $address,
                                          array $currentAddressesForOrder

    ): void
    {
        // no list was sent
        if (count($currentAddressesForOrder) == 0) {
            $orderAddress = $this->orderAddressRepository->create($orderHeader, $address);
            if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {
                $orderAddress->setShippingAddressInJson($this->serializer->serialize($address, 'json'));
            } elseif ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING) {
                $orderAddress->setBillingAddressInJson($this->serializer->serialize($address, 'json'));
            }
            $this->databaseOperations->persist($orderAddress);
        } else {
            /** @var OrderAddress $orderAddress */
            foreach ($currentAddressesForOrder as $orderAddress) {
                if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {
                    $orderAddress->setShippingAddress($address);
                    $orderAddress->setShippingAddressInJson($this->serializer->serialize($address, 'json'));
                    $this->databaseOperations->persist($orderAddress);
                    break;
                } elseif ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING) {
                    $orderAddress->setBillingAddress($address);
                    $orderAddress->setBillingAddressInJson($this->serializer->serialize($address, 'json'));
                    $this->databaseOperations->persist($orderAddress);
                    break;
                }
            }
        }
        $this->databaseOperations->flush();
    }

    /**
     * @param OrderHeader $orderHeader
     * @param string $orderStatusTypeString
     * @return void
     */
    public function setOrderStatus(OrderHeader $orderHeader, string $orderStatusTypeString): void
    {
        $orderStatusType = $this->orderStatusTypeRepository->findOneBy(['type' => $orderStatusTypeString]);

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
        $orderItem->setProductInJson($this->serializer->serialize($product,'json'));

        $priceObject = $this->priceByCountryCalculator->getPriceObject($orderItem);
        $itemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);

        $this->databaseOperations->save($itemPaymentPrice);

    }


    /**
     * @param OrderItem $orderItem
     * @param PriceObject $priceObject
     * @return void
     */
    public function savePrice(OrderItem $orderItem, PriceObject $priceObject): void
    {

        /** @var OrderItemPaymentPrice $orderItemPaymentPrice */
        $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->findOneBy(['orderItem' => $orderItem]);


        if ($orderItemPaymentPrice == null)
            $orderItemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);
        else {

            $orderItemPaymentPrice->setBasePrice($priceObject->getBasePriceAmount());
            $orderItemPaymentPrice->setDiscount($priceObject->getDiscountAmount());
            $orderItemPaymentPrice->setTaxRate($priceObject->getTaxRatePercentage());

            $orderItemPaymentPrice->getBasePriceInJson($this->serializer->serialize($priceObject->getBasePriceArray(), 'json'));
            $orderItemPaymentPrice->getDiscountsInJson($this->serializer->serialize($priceObject->getDiscountAmount(), 'json'));
            $orderItemPaymentPrice->getTaxationInJson($this->serializer->serialize($priceObject->getTaxRatePercentage(), 'json'));

        }

        $this->databaseOperations->save($orderItemPaymentPrice);
    }

    /**
     * @param OrderHeader $orderHeader
     * @param string $paymentInformation
     * @return void
     */
    public function savePayment(OrderHeader $orderHeader, string $paymentInformation): void
    {
        $orderPayment = $this->orderPaymentRepository->create($orderHeader, $paymentInformation);
        $this->databaseOperations->save($orderPayment);

    }

    /**
     * @param OrderHeader $orderHeader
     * @param array $data
     * @param OrderShipping|null $orderShipping
     * @return void
     */
    public function saveShippingData(OrderHeader $orderHeader, array $data, OrderShipping $orderShipping = null): void
    {
        if ($orderShipping == null)
            $orderShipping = $this->orderShippingRepository->create(
                $orderHeader, $data['name'], $data['value'], $data['data']);
        else {
            $orderShipping->setValue($data['value']);
        }

        $this->databaseOperations->persist($orderShipping);

        $this->databaseOperations->flush();


    }

}