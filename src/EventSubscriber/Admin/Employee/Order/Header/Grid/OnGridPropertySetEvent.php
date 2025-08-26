<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Employee\Order\Header\Grid;

use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertySetEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker,
                                private RouterInterface $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertySetEvent::EVENT_NAME => 'setProperty'
        ];

    }

    public function setProperty(GridPropertySetEvent $event): void
    {

        $route = $this->router->match($event->getRequest()->getPathInfo());

        if ($route['_route'] != 'sc_admin_order_list')
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
                            'create_link_allowed'=>false,
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


    }
}