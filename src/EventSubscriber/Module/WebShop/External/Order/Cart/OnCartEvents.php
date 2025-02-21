<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Cart;

use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
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
            CartEventTypes::BEFORE_ITEM_ADDED_TO_CART => 'beforeItemAddedToCart',
        ];

    }

    /**
     * @param CartEvent $event
     *
     * @return void
     */
    public function postCartInitialized(CartEvent $event): void
    {
        // do not create an order if the cart is empty
        if (!$this->cartSessionProductService->isCartEmpty()) {
            // check if the open order does not exist
            if (!$this->orderRead->isOpenOrder($event->getCustomer())) // only now create the order
            {
                $this->orderSave->createNewOrderFromCart($event->getCustomer());
            }

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

        if ($orderHeader == null) {
            throw new NoOpenOrderExists($event->getCustomer());
        }

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

        if ($orderHeader == null) {
            throw new NoOpenOrderExists($event->getCustomer());
        }

        $this->orderSave->addNewItem($event->getProduct(), $event->getQuantity(), $orderHeader);

    }

    /**
     * @param CartItemAddedEvent $event
     *
     * @return void
     */
    public function beforeItemAddedToCart(CartItemAddedEvent $event): void
    {
        // is there an open order
        if (!$this->orderRead->isOpenOrder($event->getCustomer())) {
            // no: create new order
            $this->orderSave->createNewOrderFromCart($event->getCustomer());
        }


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

        if ($orderHeader == null) {
            throw new NoOpenOrderExists($event->getCustomer());
        }

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

        if ($orderHeader == null) {
            throw new NoOpenOrderExists($event->getCustomer());
        }

        $orderItems = $this->orderRead->getOrderItems($orderHeader);
        $this->orderSave->removeAllItems($orderItems);

    }

}