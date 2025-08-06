<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\UI\Panel\List;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridCreateLinkEvent;
use Silecust\WebShop\Service\Component\Event\EventRouteChecker;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnGridCreateLinkEvent implements EventSubscriberInterface
{
    /**
     * @param \Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder $employeeFromUserFinder
     * @param \Silecust\WebShop\Service\Component\Event\EventRouteChecker $eventRouteChecker
     */
    public function __construct(private EmployeeFromUserFinder $employeeFromUserFinder,
                                private EventRouteChecker      $eventRouteChecker)
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
     * @param GridCreateLinkEvent $event
     * @return void
     */
    public function beforeDisplay(GridCreateLinkEvent $event): void
    {
        if ($this->employeeFromUserFinder->isLoggedInUserAnEmployee()) {
            if ($this->eventRouteChecker->isAdminRoute($event->getData()['request']))
                if (!$this->eventRouteChecker->checkFunctions($event->getData()['request'], ['employee_address'])) {
                    $event->setTemplate('@SilecustWebShop/admin/employee/customer/address/ui/panel/section/content/grid/top_level/create_link.html.twig');
                    return;
                }


            $event->setTemplate('@SilecustWebShop/admin/employee/ui/panel/section/content/grid/top_level/create_link.html.twig');


        }
    }
}
