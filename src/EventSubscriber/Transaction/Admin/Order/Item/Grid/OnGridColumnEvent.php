<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Item\Grid;

use App\Entity\OrderHeader;
use App\Event\Component\UI\Twig\GridColumnEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class OnGridColumnEvent implements EventSubscriberInterface
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GridColumnEvent::BEFORE_GRID_COLUMN_DISPLAY => 'beforeDisplay'
        ];

    }

    public function beforeDisplay(GridColumnEvent $event): void
    {

        $data = $event->getData();
        $column = $event->getData()['column'];
        $listGrid = $event->getData()['listGrid'];

        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        if ($listGrid['function'] == 'my_order_item') {
            switch ($column['propertyName']) {
                case 'id':
                    $column['value'] = $this->router->generate('my_order_item_display', ['id'=>$entity->getId()]);
                    $data['column'] = $column;
                    break;
            }

            $event->setData($data);

        }
    }
}