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
use Silecust\WebShop\Repository\OrderStatusTypeRepository;
use Silecust\WebShop\Service\Component\Database\DatabaseOperations;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Item\CartItem;
use Silecust\WebShop\Service\Transaction\Order\IdGeneration\OrderIdStrategyInterface;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatus;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * The class to create and manipulate orders
 * Note: This class only persists data and not flush it as there could be further events down the line
 * So flush is done by the end process calling it ( mostly controllers)
 */
readonly class OrderSave
{

    /**
     * @param \Silecust\WebShop\Service\Transaction\Order\Status\OrderStatus $orderStatus
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param OrderItemRepository $orderItemRepository
     * @param OrderAddressRepository $orderAddressRepository
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
        private OrderStatus                     $orderStatus,
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

        $this->orderStatus->onOrderCreate($orderHeader);

        $this->databaseOperations->persist($orderHeader);
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
         * @var  CartItem $cartObject
         */
        foreach ($cartArray as $key => $cartObject) /** @var OrderItem $orderItem */ {
            foreach ($orderItems as $orderItem) {
                if ($orderItem->getProduct()->getId() == $key) {
                    $orderItem->setQuantity($cartObject->quantity);

                }
            }
        }


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

    }

    /**
     * @param OrderHeader|null $orderHeader
     * @param CustomerAddress $address
     * @param array $currentAddressesForOrder
     * @return void
     */
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

            if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING)
                $currentAddressesForOrder[0]->setShippingAddress($address);

            elseif ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_BILLING)
                $currentAddressesForOrder[0]->setBillingAddress($address);

        }
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

    }

    public function setOrderPaymentComplete(OrderHeader $orderHeader): void
    {
        $this->orderStatus->setOrderPaymentSuccess($orderHeader);
    }

    public function setOrderPaymentSuccess(OrderHeader $orderHeader, string $paymentInformation): void
    {
        $this->orderStatus->setOrderPaymentSuccess($orderHeader);

        $orderPaymentInformation = $this->orderPaymentRepository->create($orderHeader, $paymentInformation);
        $this->databaseOperations->persist($orderPaymentInformation);
    }

    public function setOrderToInProcess(OrderHeader $orderHeader): void
    {
        $this->orderStatus->setOrderToInProcess($orderHeader);
    }

    public function setOrderToCompleted(OrderHeader $orderHeader): void
    {
        $this->orderStatus->setOrderToCompleted($orderHeader);
    }

    /**
     * @throws PriceProductTaxNotFound
     * @throws PriceProductBaseNotFound
     */
    public function addNewItem(Product $product, int $quantity, OrderHeader $orderHeader): void
    {
        // todo: check if the item already exists
        $orderItem = $this->orderItemRepository->create($orderHeader, $product, $quantity);
        $orderItem->setProductInJson($this->serializer->serialize($product, 'json'));

        $priceObject = $this->priceByCountryCalculator->getPriceObject($orderItem);
        $itemPaymentPrice = $this->orderItemPaymentPriceRepository->create($orderItem, $priceObject);

        $this->databaseOperations->persist($itemPaymentPrice);

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

            $orderItemPaymentPrice->setBasePriceInJson($this->serializer->serialize($priceObject->getBasePriceArray(), 'json'));
            $orderItemPaymentPrice->setDiscountsInJson($this->serializer->serialize($priceObject->getDiscountArray(), 'json'));
            $orderItemPaymentPrice->setTaxationInJson($this->serializer->serialize($priceObject->getTaxRateArray(), 'json'));

        }

        $this->databaseOperations->persist($orderItemPaymentPrice);
    }

    /**
     * @param OrderHeader $orderHeader
     * @param float $value
     * @param array $shippingConditions
     * @param OrderShipping|null $orderShipping
     * @return void
     */
    public function saveShippingData(OrderHeader $orderHeader, float $value, array $shippingConditions, OrderShipping $orderShipping = null): void
    {
        if ($orderShipping == null) {
            $orderShippingCreate = $this->orderShippingRepository->create($orderHeader, $value, $shippingConditions);
            $this->databaseOperations->persist($orderShippingCreate);
        } else {
            $orderShipping->setValue($value);
            $orderShipping->setShippingConditionsInJson($shippingConditions);
        }

    }

    public function incrementQuantityOfItem(OrderItem $orderItem): void
    {
        $orderItem->setQuantity($orderItem->getQuantity() + 1);
    }

    public function setOrderPaymentFailed(OrderHeader $orderHeader, string $paymentInformation): void
    {
        $this->orderStatus->setOrderPaymentFailed($orderHeader);

        $orderPaymentInformation = $this->orderPaymentRepository->create($orderHeader, $paymentInformation);
        $this->databaseOperations->persist($orderPaymentInformation);

    }
}