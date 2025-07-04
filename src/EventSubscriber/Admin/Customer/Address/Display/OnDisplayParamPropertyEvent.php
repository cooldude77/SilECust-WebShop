<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Address\Display;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnDisplayParamPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private readonly RouterInterface $router, private AdminRoutingFromRequestFinder $adminRoutingFromRequestFinder,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DisplayParamPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(DisplayParamPropertyEvent $event): void
    {



        $route = $this->router->match($event->getRequest()->getPathInfo());

        if (!in_array($route['_route'], ['sc_my_address_display']))
            return;

        $event->setDisplayParamProperties(
            [
                'title' => 'Customer Address',
                'link_id' => 'id-customer-address',
                'config' => [
                    'edit_link' => [
                        'edit_link_allowed' => true,
                        'editButtonLinkText' => 'Edit',
                        'route' => 'sc_my_address_edit',
                        'link_id' => 'id-display-customer-address'
                    ]
                ],
                'fields' => [
                    [
                        'label' => 'line 1',
                        'propertyName' => 'line1',
                    ], [
                        'label' => 'line 2',
                        'propertyName' => 'line2',
                    ], [
                        'label' => 'line 3',
                        'propertyName' => 'line3',
                    ],
                ]]
        );
    }

}