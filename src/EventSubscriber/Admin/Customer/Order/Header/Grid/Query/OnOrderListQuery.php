<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Header\Grid\Query;

use Doctrine\Common\Collections\Criteria;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
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
     * @param \Silecust\WebShop\Event\Component\Database\ListQueryEvent $listQueryEvent
     * @throws \Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException
     * @throws \Silecust\WebShop\Exception\Security\User\UserNotLoggedInException
     */
    public
    function beforeQueryList(ListQueryEvent $listQueryEvent): void
    {

        $route = $this->router->match($listQueryEvent->getRequest()->getPathInfo());
        if ($route['_route'] != 'sc_my_orders')
            return;

        $criteria = Criteria::create()->andWhere(
            Criteria::expr()->eq('oh.customer', $this->customerFromUserFinder->getLoggedInCustomer()));
        $listQueryEvent->setQuery($this->orderHeaderRepository->getQueryForSelectAllButOpenOrders($criteria));
    }
}