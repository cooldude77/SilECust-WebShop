<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Order\Header\Grid;

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

        if (!in_array($route['_route'], ['sc_my_orders', 'sc_admin_route_order_list']))
            if (!($event->getRequest()->query->get('_function') == 'order'
                && $event->getRequest()->query->get('_type') == 'list')
            ) return;

        if ($this->authorizationChecker->isGranted('ROLE_EMPLOYEE')) {
            $event->setListGridProperties([
                'function' => 'order',
                'title' => 'Order',
                'link_id' => 'id-order',
                'edit_link_allowed' => true,
                'columns' => [
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
                'createButtonConfig' => [
                    'link_id' => ' id-create-order',
                    'function' => 'order',
                    'anchorText' => 'Create Order'
                ]
            ]);

        } else {
            if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
                $event->setListGridProperties([
                    'function' => 'order',
                    'title' => 'My Orders',
                    'link_id' => 'id-order',
                    'edit_link_allowed' => false,
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
                    ]
                ]);
            }
        }


    }
}