<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Login;

use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Module\WebShop\External\Order\OrderToCart;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

readonly class OnLoginSuccess implements EventSubscriberInterface
{
    public function __construct(
        private readonly OrderRead $orderRead,
        private readonly OrderToCart $orderToCart,
        private readonly CustomerFromUserFinder $customerFromUserFinder
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess'
        ];

    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {

        try {
            $customer = $this->customerFromUserFinder->getLoggedInCustomer();

            if ($this->orderRead->isOpenOrder($customer)
            ) {  // todo handle exceptions
                $order = $this->orderRead->getOpenOrder($customer);
                $orderItems = $this->orderRead->getOrderItems($order);
                if (count($orderItems) > 0) {
                    $this->orderToCart->copyProductsFromOrderToCart($orderItems);
                }

            }
        } catch (UserNotLoggedInException $e) {
            // called if customer is logged in
        } catch (UserNotAssociatedWithACustomerException $e) {
            // don't do anything if not a customer
        }
    }
}