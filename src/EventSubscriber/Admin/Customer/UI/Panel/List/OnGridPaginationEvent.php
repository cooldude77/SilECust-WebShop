<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\UI\Panel\List;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPaginationEvent;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnGridPaginationEvent implements EventSubscriberInterface
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
            GridPaginationEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(GridPaginationEvent $event): void
    {

        if ($this->customerFromUserFinder->isLoggedInUserACustomer()) {
            $event->setTemplate(
                '@SilecustWebShop/admin/customer/ui/panel/section/content/grid/pagination/pagination.html.twig');
        }

    }
}
