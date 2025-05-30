<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Order\Header;

use Silecust\WebShop\Event\Transaction\Order\Header\BeforeOrderViewEvent;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingOrderServiceInterface;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnBeforeOrderView implements EventSubscriberInterface
{
    /**
     * @param OrderSave $orderSave
     * @param ShippingOrderServiceInterface $shippingOrderService
     */
    public function __construct(
        private readonly OrderSave                     $orderSave,
        private readonly ShippingOrderServiceInterface $shippingOrderService)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeOrderViewEvent::BEFORE_ORDER_VIEW_EVENT => ['beforeDisplay',100]
        ];

    }

    /**
     * @param BeforeOrderViewEvent $event
     * @return void
     */
    public function beforeDisplay(BeforeOrderViewEvent $event): void
    {
        $orderHeader = $event->getOrderHeader();

        // This is where API will be called.
        // todo: throw exception and handle it , how? best approach?
        $valueAndDataArray = $this->shippingOrderService->getValueAndDataArray($orderHeader);

        $this->orderSave->saveShippingData($orderHeader, $valueAndDataArray);

    }
}