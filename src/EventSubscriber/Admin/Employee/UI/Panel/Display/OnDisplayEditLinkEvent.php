<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\UI\Panel\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayEditLinkEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\TopLevelEditLinkEvent;
use Silecust\WebShop\Service\Security\User\Employee\EmployeeFromUserFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnDisplayEditLinkEvent implements EventSubscriberInterface
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
            DisplayEditLinkEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(DisplayEditLinkEvent $event): void
    {

        if ($this->employeeFromUserFinder->isLoggedInUserAlsoAEmployee()) {
            $event->setTemplate(
                '@SilecustWebShop/admin/employee/ui/panel/section/content/display/edit_link.html.twig');
        }

    }
}
