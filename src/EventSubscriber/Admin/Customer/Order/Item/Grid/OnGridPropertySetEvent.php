<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Item\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
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
            GridPropertyEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {
        $route = $this->router->match($event->getRequest()->getPathInfo());
        if (!in_array($route['_route'], ['sc_my_order_display', 'sc_admin_route_order_display']))
            if (!($event->getRequest()->query->get('_function') == 'order'
                && $event->getRequest()->query->get('_type') == 'display')
            )
                return;

        $listGrid = ['title' => 'Order Items',
            'link_id' => 'id-order-items',
            'function' => 'order_item',
            'columns' => [
                [
                    'label' => 'Id',
                    'propertyName' => 'id',
                    'action' => 'display',],
                [
                    'label' => 'Product',
                    'propertyName' => 'product'
                ],
                [
                    'label' => 'Quantity',
                    'propertyName' => 'quantity'
                ],
                [
                    'label' => 'Base Price',
                    'propertyName' => 'price'
                ],
                [
                    'label' => 'Discount',
                    'propertyName' => 'discount'
                ],
                [
                    'label' => 'Taxes',
                    'propertyName' => 'tax'
                ],
                [
                    'label' => 'Final Amount',
                    'propertyName' => 'finalAmount'
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
                    'link_id' => ' id-order-item',
                    'route' => 'sc_my_order_item_display',
                    'anchorText' => 'Display Order Item'
                ]
            ]];
        $event->setListGridProperties($listGrid);

    }
}