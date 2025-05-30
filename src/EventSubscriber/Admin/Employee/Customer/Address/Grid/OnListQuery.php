<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid;

use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Repository\CustomerRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnListQuery implements EventSubscriberInterface
{
    public function __construct(
        private readonly CustomerRepository        $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly RouterInterface           $router
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
    public function beforeQueryList(ListQueryEvent $event): void
    {

        $route = $this->router->match($event->getRequest()->getPathInfo());
        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_admin_customer_display', 'sc_admin_customer_address_list']))
            return;
        if ($event->getData()['event_caller'] != CustomerAddressController::LIST_IDENTIFIER)
            return;


        $customer = $this->customerRepository->find($event->getRequest()->get('id'));

        $event->setQuery($this->customerAddressRepository->getQueryForSelectByCustomer($customer));

    }

}