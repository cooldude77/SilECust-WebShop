<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\UI\Panel\List;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPaginationEvent;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
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
    public function __construct(private readonly EmployeeFromUserFinder $employeeFromUserFinder)
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

        if ($this->employeeFromUserFinder->isLoggedInUserAlsoAEmployee()) {
            $event->setTemplate(
                '@SilecustWebShop/admin/employee/ui/panel/section/content/grid/pagination/pagination.html.twig');
        }

    }
}
