<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Customer\Order\Header;

use Doctrine\Common\Collections\Criteria;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnOrderListQuery implements EventSubscriberInterface
{
    public function __construct(
        private CustomerFromUserFinder $customerFromUserFinder,
        private OrderHeaderRepository  $orderHeaderRepository,
        private RouterInterface        $router
    )
    {
    }

    public
    static function getSubscribedEvents(): array
    {
        return [
            ListQueryEvent::BEFORE_LIST_QUERY => 'beforeQueryList'
        ];

    }

    /**
     * @throws UserNotLoggedInException
     */
    public
    function beforeQueryList(ListQueryEvent $listQueryEvent): void
    {

        $route = $this->router->match($listQueryEvent->getRequest()->getPathInfo());
        if (!in_array($route['_route'], ['sc_my_orders']))
            return;

        if ($this->customerFromUserFinder->isLoggedInUserACustomer())
            try {
                $criteria = Criteria::create();
                //$criteria->andWhere(
                  //  Criteria::expr()->eq('oh.customer', $this->customerFromUserFinder->getLoggedInCustomer()));
                $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelectAllButOpenOrders($criteria));
            } catch (UserNotAssociatedWithACustomerException $e) {

            }
    }
}