<?php

namespace App\EventSubscriber\Transaction\Admin\Customer\Address;

use App\Event\Component\Database\ListQueryEvent;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Repository\CustomerAddressRepository;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnCustomerAddressListQuery implements EventSubscriberInterface
{
    public function __construct(
        private CustomerFromUserFinder    $customerFromUserFinder,
        private CustomerAddressRepository $customerAddressRepository,
        private RouterInterface           $router
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

        if ($route['_route'] != 'my_addresses')
            return;

        if ($this->customerFromUserFinder->isLoggedInUserAlsoACustomer())
            try {
                $listQueryEvent->setQuery($this->customerAddressRepository->getQueryForSelectByCustomer($this->customerFromUserFinder->getLoggedInCustomer()));
            } catch (UserNotAssociatedWithACustomerException $e) {

            }
// todo: logic for employee in this or another event

//        if ($this->employeeFromUserFinder->isLoggedInUserAlsoAEmployee())
        //          $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelect());

    }
}