<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Cart;

use App\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use App\Event\Module\WebShop\External\Cart\CartEvent;
use App\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use App\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use App\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use App\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use App\Exception\Module\WebShop\External\Order\NoOrderItemExistsWith;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Module\WebShop\External\Order\OrderRead;
use App\Service\Module\WebShop\External\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
readonly class OnCartEvents implements EventSubscriberInterface
{
    /**
     * @param OrderSave                 $orderSave
     * @param OrderRead                 $orderRead
     * @param CartSessionProductService $cartSessionProductService
     */
    public function __construct(private OrderSave $orderSave,
        private readonly OrderRead $orderRead,
        private readonly CartSessionProductService $cartSessionProductService,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CartEventTypes::POST_CART_INITIALIZED => 'postCartInitialized',
            CartEventTypes::ITEM_ADDED_TO_CART => 'newItemAdded',
            CartEventTypes::ITEM_DELETED_FROM_CART => 'itemDeleted',
            CartEventTypes::CART_CLEARED_BY_USER => 'cartCleared',
            CartEventTypes::POST_CART_QUANTITY_UPDATED => 'onCartQuantityUpdated',
        ];

    }

    /**
     * @param CartEvent $event
     *
     * @return void
     */
    public function postCartInitialized(CartEvent $event): void
    {
        if ($this->cartSessionProductService->isCartEmpty()) {
               if(!$this->orderRead->isOpenOrder($event->getCustomer()))
                   $this->orderSave->createNewOrderFromCart($event->getCustomer());

        }

    }

    /**
     * @param CartEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function onCartQuantityUpdated(CartEvent $event): void
    {


        $orderHeader = $this->orderRead->getOpenOrder($event->getCustomer());

        if($orderHeader == null)
            throw new NoOpenOrderExists($event->getCustomer());

        $orderItems = $this->orderRead->getOrderItems($orderHeader);


        $this->orderSave->updateOrderItemsFromCartArray(
            $this->cartSessionProductService->getCartArray(),
            $orderItems
        );

    }

    /**
     * @param CartItemAddedEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function newItemAdded(CartItemAddedEvent $event): void
    {
        // todo : check for open order
        // assuming it exists

        $orderHeader = $this->orderRead->getOpenOrder($event->getCustomer());
        if($orderHeader == null)
            throw new NoOpenOrderExists($event->getCustomer());

        $orderItem = $this->orderRead->createOrderItem(
            $orderHeader, $event->getProduct(),
            $event->getQuantity()
        );
        if($orderItem == null)
            throw new NoOrderItemExistsWith($event->getCustomer(),
                $event->getProduct(),
                $event->getQuantity());


        $this->orderSave->updateOrderAddItem($orderItem);

    }

    /**
     * @param CartItemDeletedEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function itemDeleted(CartItemDeletedEvent $event): void
    {

        $orderHeader = $this->orderRead->getOpenOrder($event->getCustomer());

        if($orderHeader == null)
            throw new NoOpenOrderExists($event->getCustomer());

        $orderItems = $this->orderRead->getOrderItems($orderHeader);
        $this->orderSave->updateOrderRemoveItem($event->getProduct(), $orderItems);

    }

    /**
     * @param CartClearedByUserEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function cartCleared(CartClearedByUserEvent $event): void
    {
        $orderHeader = $this->orderRead->getOpenOrder($event->getCustomer());

        if($orderHeader == null)
            throw new NoOpenOrderExists($event->getCustomer());

        $orderItems = $this->orderRead->getOrderItems($orderHeader);
        $this->orderSave->removeAllItems($orderItems);

    }

}