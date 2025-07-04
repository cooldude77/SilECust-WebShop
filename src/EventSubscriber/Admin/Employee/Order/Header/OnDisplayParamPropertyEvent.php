<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Header;

use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRoutingFromRequestFinder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class OnDisplayParamPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private readonly RouterInterface $router,
                              private  AdminRoutingFromRequestFinder    $adminRoutingFromRequestFinder
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

        // To check for both stand-alone test case where admin panel is not called
        // and also where admin panel is called from UI
        if (!in_array($route['_route'], ['sc_admin_panel', 'sc_admin_route_order_display']))
            return;

        // if admin panel is called
        // check proper function
        if (in_array($route['_route'], ['sc_admin_panel'])) {
            $object = $this->adminRoutingFromRequestFinder->getAdminRouteObject($event->getRequest());

            if ($object->getFunction() != 'order')
                return;

        }
        $event->setDisplayParamProperties(
            [
                'title' => 'Order',
                'link_id' => 'id-order',
                'config' => [
                    'edit_link' => [
                        'edit_link_allowed' => true,
                        'use_entity_field_as_id' => 'generatedId'
                    ]
                ],
                'fields' => [
                    [
                        'label' => 'Order Number',
                        'propertyName' => 'generatedId',
                        'action' => 'display',],
                    [
                        'label' => 'Date Of Order',
                        'propertyName' => 'dateTimeOfOrder'
                    ],
                    [
                        'label' => 'Status',
                        'propertyName' => 'orderStatusType'
                    ],
                    [
                        'label' => 'Order Value',
                        'propertyName' => 'orderValue'
                    ],
                ],
            ]
        );
    }

}