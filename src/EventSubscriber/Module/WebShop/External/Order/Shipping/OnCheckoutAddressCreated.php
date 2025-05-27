<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Shipping;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressChosenEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressCreatedEvent;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Exception\Module\WebShop\External\Shipping\ShippingChargesNotProvided;
use Silecust\WebShop\Exception\Module\WebShop\External\Shipping\ShippingRecordByKeyNotFound;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingPricingConditionsResponseResolverInterface;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnCheckoutAddressCreated implements EventSubscriberInterface
{
    /**
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @param OrderRead $orderRead
     * @param OrderSave $orderSave
     * @param ShippingPricingConditionsResponseResolverInterface $shippingOrderService
     * @param CheckOutAddressSession $checkOutAddressSession
     */
    public function __construct(
        private CustomerFromUserFinder                             $customerFromUserFinder,
        private OrderRead                                          $orderRead,
        private OrderSave                                          $orderSave,
        private ShippingPricingConditionsResponseResolverInterface $shippingOrderService,
        private CheckOutAddressSession                             $checkOutAddressSession)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddressCreatedEvent::EVENT_NAME => 'onAddressCreated'
        ];

    }

    /**
     * @param AddressCreatedEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException|ShippingChargesNotProvided
     */
    public function onAddressCreated(AddressCreatedEvent $event): void
    {

        $customer = $this->customerFromUserFinder->getLoggedInCustomer();

        $orderHeader = $this->orderRead->getOpenOrder($customer);
        if ($orderHeader == null)
            throw  new NoOpenOrderExists($customer);

        $address = $event->getCustomerAddress();

        // This is where API will be called.
           if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING
            && $this->checkOutAddressSession->isShippingAddressSet()
            && $this->checkOutAddressSession->getShippingAddress()->getId() == $address->getId()
        ) {

            // Check the structure in DevShippingChargesClass
            // get values from API
            $shippingConditions = $this->shippingOrderService->getShippingChargesConditionsFromAPI($orderHeader);

            if ($shippingConditions == null)
                throw  new ShippingChargesNotProvided();

            foreach ($shippingConditions as $value) {
                try {
                    $shippingRecord = $this->orderRead->findShippingDataByKey($orderHeader, $value['name']);
                    $this->orderSave->saveShippingData($orderHeader, $value, $shippingRecord);
                } catch (ShippingRecordByKeyNotFound) {
                    $this->orderSave->saveShippingData($orderHeader, $value);
                }
            }
        }

    }
}