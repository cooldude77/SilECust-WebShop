<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\UI\Panel\List;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridCreateLinkEvent;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnGridCreateLinkEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridCreateLinkEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(GridCreateLinkEvent $event): void
    {

        if ($this->customerFromUserFinder->isLoggedInUserACustomer()) {
            $event->setTemplate('@SilecustWebShop/admin/customer/ui/panel/section/content/grid/top_level/create_link.html.twig');
        }

    }
}
