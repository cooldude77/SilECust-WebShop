<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Address;

use Silecust\WebShop\Event\Module\WebShop\External\Address\CheckoutAddressChosenEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Address\Types\CheckoutAddressEventTypes;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnCheckoutAddressChosen implements EventSubscriberInterface
{
    public function __construct(private OrderRead $orderRead,
        private OrderSave $orderSave
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutAddressEventTypes::POST_ADDRESS_CHOSEN => 'onAddressChosen'
        ];

    }

    /**
     * @param CheckoutAddressChosenEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function onAddressChosen(CheckoutAddressChosenEvent $event): void
    {

        $customer = $event->getCustomer();

        $orderHeader = $this->orderRead->getOpenOrder($customer);
        if($orderHeader == null)
            throw  new NoOpenOrderExists();

        $address = $event->getCustomerAddress();

        $listAddresses = $this->orderRead->getAddresses($orderHeader);

        $this->orderSave->createOrUpdate($orderHeader, $address,$listAddresses);

    }
}