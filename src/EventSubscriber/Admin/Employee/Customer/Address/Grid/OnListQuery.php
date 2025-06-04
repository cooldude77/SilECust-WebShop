<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Customer\Address\Grid;

use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnListQuery implements EventSubscriberInterface
{
    public function __construct(
        private readonly CustomerRepository        $customerRepository,
        private readonly CustomerAddressRepository $customerAddressRepository,
        private readonly EventRouteChecker $eventRouteChecker
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

        if (!
        $this->eventRouteChecker->isInRouteList($event->getRequest(), ['sc_admin_panel', 'sc_admin_customer_display']))
            return;
        if (!
        ($this->eventRouteChecker->hasFunction($event->getRequest(), 'customer')
            || ($this->eventRouteChecker->hasFunction($event->getRequest(), 'customer_address'))
        )
        )
            return;

        if ($event->getData()['event_caller'] != CustomerAddressController::LIST_IDENTIFIER)
            return;

        $customer = $this->customerRepository->find($event->getRequest()->get('id'));

        $event->setQuery($this->customerAddressRepository->getQueryForSelectByCustomer($customer));

    }

}