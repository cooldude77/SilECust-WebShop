<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Cart;

use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Exception\Module\WebShop\External\Order\NoOpenOrderExists;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
readonly class OnCartEvents implements EventSubscriberInterface
{
    /**
     * @param OrderSave $orderSave
     * @param OrderRead $orderRead
     * @param \Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder $customerFromUserFinder
     * @param CartProductManager $cartSessionProductService
     */
    public function __construct(private OrderSave              $orderSave,
                                private OrderRead              $orderRead,
                                private CustomerFromUserFinder $customerFromUserFinder,
                                private CartProductManager     $cartSessionProductService,
    )
    {
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

        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();

            // check if the open order does not exist
            if (!$this->orderRead->isOpenOrder($customer)) // only now create the order
            {
                $this->orderSave->createNewOrderFromCart($customer);
            }
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // user is not logged in, no need to create Database Entry
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

        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();


            $orderHeader = $this->orderRead->getOpenOrder($customer);

            if ($orderHeader == null) {
                throw new NoOpenOrderExists($customer);
            }

            $orderItems = $this->orderRead->getOrderItems($orderHeader);

            $this->orderSave->updateOrderItemsFromCartArray(
                $this->cartSessionProductService->getCartArray(),
                $orderItems
            );
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // no user logged in , database not affected
        }

    }

    /**
     * @param CartItemAddedEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function newItemAdded(CartItemAddedEvent $event): void
    {
        // todo : check for open order
        // assuming it exists
        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();

            $orderHeader = $this->orderRead->getOpenOrder($customer);

            if ($orderHeader == null) {
                throw new NoOpenOrderExists($customer);
            }

            if ($this->orderRead->orderItemExists($orderHeader, $event->getProduct())) {
                $item = $this->orderRead->getOrderItem($orderHeader, $event->getProduct());
                $this->orderSave->incrementQuantityOfItem($item);
            } else
                $this->orderSave->addNewItem($event->getProduct(), $event->getQuantity(), $orderHeader);
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // no user logged in , database not affected
        }
    }

    /**
     * @param CartItemAddedEvent $event
     *
     * @return void
     */
    public function beforeItemAddedToCart(CartItemAddedEvent $event): void
    {
        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();
            // is there an open order
            if (!$this->orderRead->isOpenOrder($customer)) {
                // no: create new order
                $this->orderSave->createNewOrderFromCart($customer);
            }
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // no user logged in , database not affected
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
        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();

            $orderHeader = $this->orderRead->getOpenOrder($customer);

            if ($orderHeader == null) {
                throw new NoOpenOrderExists($customer);
            }

            $orderItems = $this->orderRead->getOrderItems($orderHeader);
            $this->orderSave->updateOrderRemoveItem($event->getProduct(), $orderItems);
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // no user logged in , database not affected
        }
    }

    /**
     * @param CartClearedByUserEvent $event
     *
     * @return void
     * @throws NoOpenOrderExists
     */
    public function cartCleared(CartClearedByUserEvent $event): void
    {
        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();

            $orderHeader = $this->orderRead->getOpenOrder($customer);

            if ($orderHeader == null) {
                throw new NoOpenOrderExists($customer);
            }

            $orderItems = $this->orderRead->getOrderItems($orderHeader);
            $this->orderSave->removeAllItems($orderItems);
        } catch (UserNotAssociatedWithACustomerException|UserNotLoggedInException $e) {

            // no user logged in , database not affected
        }
    }

}