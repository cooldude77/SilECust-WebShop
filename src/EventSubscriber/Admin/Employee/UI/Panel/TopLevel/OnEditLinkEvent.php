<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\UI\Panel\TopLevel;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\TopLevelEditLinkEvent;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnEditLinkEvent implements EventSubscriberInterface
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
            TopLevelEditLinkEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(TopLevelEditLinkEvent $event): void
    {

        if ($this->employeeFromUserFinder->isLoggedInUserAnEmployee()) {
            $event->setTemplate(
                '@SilecustWebShop/admin/employee/ui/panel/section/content/grid/top_level/edit_link.html.twig');
        }

    }
}
