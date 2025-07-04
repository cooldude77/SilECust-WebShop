<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Item\Display;

use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\DisplayParametersEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OnDisplayPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
                                private readonly RouterInterface      $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DisplayParametersEvent::GET_DISPLAY_PROPERTY_EVENT => 'setProperty'
        ];

    }

    public function setProperty(DisplayParametersEvent $event): void
    {
        $route = $this->router->match($event->getRequest()->getPathInfo());
        if (!in_array($route['_route'], ['sc_my_order_display', 'sc_admin_route_order_display']))
            if (!($event->getRequest()->query->get('_function') == 'order_item' // order item list is never shown standalone
                && $event->getRequest()->query->get('_type') == 'display')
            )
                return;

        $event->setParameterList(
            ['title' => 'Price',
                'link_id' => 'id-price',
                'config' => [
                    'edit_link' => [
                        'edit_link_allowed' => false,
                        'editButtonLinkText' => 'Edit',
                        'route' => 'sc_my_address_edit',
                        'link_id' => 'id-display-customer-address']
                ],
                'fields' => [
                    [
                        'label' => 'id',
                        'propertyName' => 'id',
                    ],
                ]]);
    }
}