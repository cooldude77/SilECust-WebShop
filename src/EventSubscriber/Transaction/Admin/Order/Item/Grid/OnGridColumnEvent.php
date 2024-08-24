<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Item\Grid;

use App\Entity\OrderItem;
use App\Event\Component\UI\Panel\List\GridColumnEvent;
use App\Service\Transaction\Order\Price\Item\ItemPriceCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class OnGridColumnEvent implements EventSubscriberInterface
{
    public function __construct(private readonly RouterInterface $router, private readonly ItemPriceCalculator $itemPriceCalculator)
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
            if (!($event->getData()['request']->query->get('_function') == 'order' // order item list is never shown standalone
                && $event->getData()['request']->query->get('_type') == 'display')
            )
                return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        $listGrid = $event->getData()['listGrid'];

        /** @var OrderItem $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'id':
                if ($route['_route'] == 'my_order_display')
                    $column['value'] = $this->router->generate('my_order_item_display', ['id' => $entity->getId()]);
                else
                    $column['value'] = $this->router->generate('admin_panel', [
                        '_function' => 'order_item',
                        '_type' => 'display',
                        'id' => $entity->getId()]);

                break;
            case 'price':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getBasePrice();
                break;

            case 'discount':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getDiscount();
                break;
            case 'tax':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getTaxRate();
                break;
            case 'finalAmount':
                $column['value'] = $this->itemPriceCalculator->getPriceWithTax($entity);
                break;
        }

        $data['column'] = $column;

        $event->setData($data);

    }

}