<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Order\Header;

use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnOrderListQuery implements EventSubscriberInterface
{
    public function __construct(
        private CustomerFromUserFinder $customerFromUserFinder,
        private EmployeeFromUserFinder $employeeFromUserFinder,
        private OrderHeaderRepository  $orderHeaderRepository,
        private RouterInterface        $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ListQueryEvent::BEFORE_LIST_QUERY => 'beforeQueryList'
        ];

    }

    /**
     * @throws UserNotLoggedInException
     */
    public function beforeQueryList(ListQueryEvent $listQueryEvent): void
    {

        $route = $this->router->match($listQueryEvent->getRequest()->getPathInfo());
        if (!in_array($route['_route'], ['sc_admin_route_order_list', 'sc_my_orders']))
            if (!($listQueryEvent->getRequest()->query->get('_function') == 'order'
                && $listQueryEvent->getRequest()->query->get('_type') == 'list')
            )
                return;

        if ($this->customerFromUserFinder->isLoggedInUserACustomer())
            try {
                $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelectByCustomer($this->customerFromUserFinder->getLoggedInCustomer()));
            } catch (UserNotAssociatedWithACustomerException $e) {

            }

        if ($this->employeeFromUserFinder->isLoggedInUserAnEmployee())
            $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelectAllButOpenOrders());

    }
}