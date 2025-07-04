<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Item\Display;

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
        private readonly RouterInterface $router
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
        if ($route['_route'] != 'sc_my_order_item_display')
            return;

        $event->setParameterList(
            [
                'title' => 'Price',
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