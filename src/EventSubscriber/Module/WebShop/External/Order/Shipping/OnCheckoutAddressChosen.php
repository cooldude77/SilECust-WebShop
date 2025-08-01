<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Shipping;

use Silecust\WebShop\Entity\CustomerAddress;
use Silecust\WebShop\Entity\OrderShipping;
use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressChosenEvent;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Exception\Module\WebShop\External\Shipping\ShippingChargesNotProvided;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingPricingConditionsResponseResolverInterface;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function Symfony\Component\Translation\t;

class OnCheckoutAddressChosen implements EventSubscriberInterface
{
    /**
     * @param OrderSave $orderSave
     * @param ShippingPricingConditionsResponseResolverInterface $shippingOrderService
     */
    public function __construct(
        private readonly CustomerFromUserFinder                             $customerFromUserFinder,
        private readonly OrderRead                                          $orderRead,
        private readonly OrderSave                                          $orderSave,
        private readonly ShippingPricingConditionsResponseResolverInterface $shippingOrderService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddressChosenEvent::EVENT_NAME => 'onAddressChosen'
        ];

    }

    /**
     * @param AddressChosenEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException|ShippingChargesNotProvided
     */
    public function onAddressChosen(AddressChosenEvent $event): void
    {

        $customer = $this->customerFromUserFinder->getLoggedInCustomer();

        $orderHeader = $this->orderRead->getOpenOrder($customer);
        if ($orderHeader == null)
            throw  new NoOpenOrderExists($customer);

        $address = $event->getCustomerAddress();

        // This is where API will be called.
        // todo: throw exception and handle it , how? best approach?
        //todo: this should move to checkout controller
        if ($address->getAddressType() == CustomerAddress::ADDRESS_TYPE_SHIPPING) {

            // Check the structure in DevShippingChargesClass
            // get values from API
            // which is in JSON Format
            $shippingConditions = json_decode($this->shippingOrderService->getShippingChargesConditionsFromAPI($orderHeader),true);

            if ($shippingConditions == null)
                throw  new ShippingChargesNotProvided();

            $this->orderSave->saveShippingData($orderHeader, $shippingConditions[OrderShipping::TOTAL_SHIPPING_VALUE], $shippingConditions);
        }

    }
}