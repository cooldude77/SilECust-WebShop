<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Header;

use App\Event\Component\Database\ListQueryEvent;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Repository\OrderHeaderRepository;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
        if (!in_array( $route['_route'] ,['order_list','my_orders']))
            return;

        if ($this->customerFromUserFinder->isLoggedInUserAlsoACustomer())
            try {
                $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelectByCustomer($this->customerFromUserFinder->getLoggedInCustomer()));
            } catch (UserNotAssociatedWithACustomerException $e) {

            }

        if ($this->employeeFromUserFinder->isLoggedInUserAlsoAEmployee())
            $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelect());

    }
}