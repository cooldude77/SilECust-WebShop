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
        $route = $this->router->match($event->getData()['request']->getPathInfo());
        if (!in_array($route['_route'], ['my_order_display', 'order_display']))
            if (!($event->getData()['request']->query->get('_function') == 'order_item' // order item list is never shown standalone
                && $event->getData()['request']->query->get('_type') == 'list')
            )
                return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        $listGrid = $event->getData()['listGrid'];

        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'id':
                if ($route['_route'] == 'my_order_display')
                    $column['value'] = $this->router->generate('my_order_item_display', ['id' => $entity->getId()]);
                else
                    $column['value'] = $this->router->generate('admin_panel',[
                        '_function'=>'order_item',
                        '_type'=>'display',
                        'id' => $entity->getId()]);

                $data['column'] = $column;
                break;
        }

        $event->setData($data);

    }

}