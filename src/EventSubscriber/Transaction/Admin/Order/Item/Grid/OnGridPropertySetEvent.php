<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Item\Grid;

use App\Event\Component\UI\Twig\GridPropertyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class OnGridPropertySetEvent implements EventSubscriberInterface
{
    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridPropertyEvent::LIST_GRID_PROPERTY_FOR_ORDERS => 'setProperty'
        ];

    }

    public function setProperty(GridPropertyEvent $event): void
    {


        if ($this->authorizationChecker->isGranted('ROLE_EMPLOYEE')) {
            $listGrid = ['title' => 'Order Items',
                'link_id' => 'id-order-items',
                'function' => 'order_item',
                'columns' => [['label' => 'Id',
                    'propertyName' => 'id',
                    'action' => 'display',],],
                'createButtonConfig' => ['link_id' => ' id-create-order-item',
                    'id' => $event->getData()['id'],
                    'function' => 'order_item',
                    'anchorText' => 'Create Order Item']];


        } else {
            if ($this->authorizationChecker->isGranted('ROLE_CUSTOMER')) {
                $listGrid = ['title' => 'Order Items',
                    'link_id' => 'id-order-items',
                    'function' => 'order_item',
                    'columns' => [['label' => 'Id',
                        'propertyName' => 'id',
                        'action' => 'display',],],
                    'create_button_allowed' => false,];
            }
        }

        $event->setListGridProperties($listGrid);

    }
}