<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Address;

use App\Event\Module\WebShop\External\Address\CheckoutAddressCreatedEvent;
use App\Event\Module\WebShop\External\Address\Types\CheckoutAddressEventTypes;
use App\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnCheckoutAddressCreated implements EventSubscriberInterface
{
    public function __construct(private OrderRead $orderRead,
        private OrderSave $orderSave
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutAddressEventTypes::POST_ADDRESS_CREATE => 'onAddressCreated'
        ];

    }

    public function onAddressCreated(CheckoutAddressCreatedEvent $event): void
    {

        $customer = $event->getCustomer();

        $orderHeader = $this->orderRead->getOpenOrder($customer);

        if($orderHeader == null)
            throw new NoOpenOrderExists();

        $address = $event->getCustomerAddress();

        $listAddresses = $this->orderRead->getAddresses($orderHeader);

        $this->orderSave->createOrUpdate($orderHeader, $address, $listAddresses);


    }
}