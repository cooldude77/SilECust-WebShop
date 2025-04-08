<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Customer\Address;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridCreateLinkEvent;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class OnGridCreateLinkEvent implements EventSubscriberInterface
{
    public function __construct(private readonly RouterInterface        $router,
                                private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridCreateLinkEvent::BEFORE_GRID_CREATE_LINK => 'beforeCreate'
        ];

    }

    public function beforeCreate(GridCreateLinkEvent $event): void
    {

        $route = $this->router->match($event->getData()['request']->getPathInfo());

        if (!in_array($route['_route'], ['sc_my_addresses', 'customer_addresses']))
            return;

        $data = $event->getData();
        $listGrid = $event->getData()['config'];

        $customer = $this->customerFromUserFinder->getLoggedInCustomer();

        if ($route['_route'] ==  'sc_my_addresses')
            $data['linkValue'] = ($this->router->generate('sc_my_address_create'));

        // to do : what employee should see

        $event->setData($data);

    }
}