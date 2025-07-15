<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Header\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private readonly RouterInterface $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {

        $route = $this->router->match($event->getRequest()->getPathInfo());

        if (!in_array($route['_route'], ['sc_my_orders']))
            return;

        $event->setListGridProperties([
            'function' => 'order',
            'title' => 'My Orders',
            'link_id' => 'id-order',
            'columns' => [
                [
                    'label' => 'Order Id',
                    'propertyName' => 'generatedId',
                    'action' => 'display',
                ],
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
            'config' => [
                'create_link' => [
                    'create_link_allowed' => false,
                ],
                'edit_link' => [
                    'edit_link_allowed=>false'
                ],
                'display_link' => [
                    'link_id' => ' id-display-order',
                    'route' => 'sc_my_order_display',
                    'anchorText' => 'Display Order',
                    'redirect_upon_success_route' => 'sc_my_orders'
                ]
            ],

        ]);

    }
}