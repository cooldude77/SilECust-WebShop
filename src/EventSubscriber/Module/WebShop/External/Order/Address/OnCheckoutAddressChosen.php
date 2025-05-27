<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Address;

use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressChosenEvent;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnCheckoutAddressChosen implements EventSubscriberInterface
{
    public function __construct(
        private readonly CustomerFromUserFinder $customerFromUserFinder,
        private readonly OrderRead              $orderRead,
        private readonly OrderSave              $orderSave
    )
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
     * @throws UserNotLoggedInException
     */
    public function onAddressChosen(AddressChosenEvent $event): void
    {

        $customer = $this->customerFromUserFinder->getLoggedInCustomer();

        $orderHeader = $this->orderRead->getOpenOrder($customer);
        if ($orderHeader == null)
            throw  new NoOpenOrderExists($customer);

        $address = $event->getCustomerAddress();

        $listAddresses = $this->orderRead->getAddresses($orderHeader);

        // Add conditions on update
        $this->orderSave->createOrUpdateAddress($orderHeader, $address, $listAddresses);

    }
}