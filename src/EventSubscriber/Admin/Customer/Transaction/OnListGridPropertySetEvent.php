<?php

namespace App\EventSubscriber\Admin\Customer\Transaction;

use App\Event\Transaction\Order\Admin\Header\ListGridPropertyForOrderListEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnListGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ListGridPropertyForOrderListEvent::LIST_GRID_PROPERTY_FOR_ORDERS => 'setProperty'
        ];

    }

    public function setProperty(ListGridPropertyForOrderListEvent $event): void
    {

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