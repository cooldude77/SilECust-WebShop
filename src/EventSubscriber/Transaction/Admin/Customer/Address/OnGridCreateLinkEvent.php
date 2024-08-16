<?php

namespace App\EventSubscriber\Transaction\Admin\Customer\Address;

use App\Event\Component\UI\Twig\GridCreateLinkEvent;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class OnGridCreateLinkEvent implements EventSubscriberInterface
{
    public function __construct(private readonly RouterInterface        $router,
                                private readonly CustomerFromUserFinder $customerFromUserFinder,
                                private readonly RequestStack           $requestStack)
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


        $data = $event->getData();
        $listGrid = $event->getData()['config'];

        $customer = $this->customerFromUserFinder->getLoggedInCustomer();

        if ($this->router->match($this->requestStack->getCurrentRequest()->getPathInfo(), 'my_addresses'))
            $data['linkValue'] = ($this->router->generate('my_address_create'));

        $event->setData($data);

    }
}