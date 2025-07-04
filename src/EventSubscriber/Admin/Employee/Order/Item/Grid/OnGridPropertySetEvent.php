<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Item\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private readonly RouterInterface      $router
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
        if (!in_array($route['_route'], ['sc_my_order_display', 'sc_admin_order_display']))
            if (!($event->getRequest()->query->get('_function') == 'order'
                && $event->getRequest()->query->get('_type') == 'display')
            )
                return;

            $listGrid = ['title' => 'Order Items',
                'link_id' => 'id-order-items',
                'function' => 'order_item',
                'edit_link_allowed' => true,
                'columns' => [
                    [
                        'label' => 'Id',
                        'propertyName' => 'id',
                        'action' => 'display',
                    ], [
                        'label' => 'Quantity',
                        'propertyName' => 'quantity'
                    ],
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
                'createButtonConfig' => ['link_id' => ' id-create-order-item',
                    'id' => $event->getData()['id'],
                    'function' => 'order_item',
                    'anchorText' => 'Create Order Item']];


        $event->setListGridProperties($listGrid);

    }
}