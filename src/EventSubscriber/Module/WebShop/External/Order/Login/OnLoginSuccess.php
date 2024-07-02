<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Login;

use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Module\WebShop\External\Order\OrderRead;
use App\Service\Module\WebShop\External\Order\OrderToCart;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
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
                $items = $this->orderRead->getOrderItems($order);
                if (count($items) > 0) {
                    $this->orderToCart->copyProductsFromOrderToCart($items);
                }

            }
        } catch (UserNotLoggedInException $e) {
            // called if customer is logged in
        } catch (UserNotAssociatedWithACustomerException $e) {
            // don't do anything if not a customer
        }
    }
}