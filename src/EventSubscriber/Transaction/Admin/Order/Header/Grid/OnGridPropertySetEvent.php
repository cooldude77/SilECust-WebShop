<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Header\Grid;

use App\Event\Component\UI\Twig\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker,
    private readonly  RouterInterface $router
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertyEvent::LIST_GRID_PROPERTY_FOR_ORDERS => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {

        $route = $this->router->match($event->getRequest()->getPathInfo());

        if (!in_array($route['_route'], ['my_orders', 'order_list']))
            if (!($event->getRequest()->query->get('_function') == 'order'
                && $event->getRequest()->query->get('_type') == 'list')
            )   return;

        if ($this->authorizationChecker->isGranted('ROLE_EMPLOYEE')) {
            $event->setListGridProperties([
                'function' => 'order',
                'title' => 'Order',
                'link_id' => 'id-order',
                'edit_link_allowed' => true,
                'columns' => [
                    [
                        'label' => 'Id',
                        'propertyName' => 'id',
                        'action' => 'display',],
                    [
                        'label' => 'Date Of Order',
                        'propertyName' => 'dateTimeOfOrder'
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
                            'label' => 'Id',
                         'propertyName' => 'id',
                         'action' => 'display',
                            ],
                        [
                            'label' => 'Date Of Order',
                         'propertyName' => 'dateTimeOfOrder'
                        ],
                    ]
                ]);
            }
        }


    }
}