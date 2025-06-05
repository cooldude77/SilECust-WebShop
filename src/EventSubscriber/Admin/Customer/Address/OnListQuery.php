<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address;

use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnListQuery implements EventSubscriberInterface
{
    public function __construct(
        private CustomerFromUserFinder    $customerFromUserFinder,
        private CustomerAddressRepository $customerAddressRepository,
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
        $this->eventRouteChecker->isInRouteList($event->getRequest(), ['sc_my_addresses']))
            return;

        if ($this->customerFromUserFinder->isLoggedInUserACustomer())
            try {
                $event->setQuery($this->customerAddressRepository->getQueryForSelectByCustomer($this->customerFromUserFinder->getLoggedInCustomer()));
            } catch (UserNotAssociatedWithACustomerException $e) {

            }
    }
}