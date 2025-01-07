<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Item\Display;

use App\Event\Component\UI\Panel\Display\DisplayParametersEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OnItemDisplayPropertyEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker,
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
        if (!in_array($route['_route'], ['my_order_display', 'sc_admin_route_order_display']))
            if (!($event->getRequest()->query->get('_function') == 'order_item' // order item list is never shown standalone
                && $event->getRequest()->query->get('_type') == 'display')
            )
                return;

        $event->setParameterList(
            ['title' => 'Price',
                'link_id' => 'id-price',
                'editButtonLinkText' => 'Edit',
                'fields' => [
                    [
                        'label' => 'id',
                        'propertyName' => 'id',
                    ],
                ]]);
    }
}