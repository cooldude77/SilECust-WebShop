<?php

namespace App\EventSubscriber\Transaction\Admin\Order\Header\Grid;

use App\Entity\OrderHeader;
use App\Event\Component\UI\Twig\GridColumnEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnGridColumnEvent implements EventSubscriberInterface
{
    /**
     * @param RouterInterface $router
     */
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridColumnEvent::BEFORE_GRID_COLUMN_DISPLAY => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(GridColumnEvent $event): void
    {

        $route = $this->router->match($event->getData()['request']->getPathInfo());

        if (!in_array($route['_route'], ['my_orders', 'order_list']))
            if (!($event->getData()['request']->query->get('_function') == 'order'
                && $event->getData()['request']->query->get('_type') == 'list')
            )
                return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'id':
                if ($route['_route'] == 'my_orders')
                    $column['value'] = $this->router->generate('my_order_display', ['id' => $entity->getId()]);
                else
                    $column['value'] = $this->router->generate('admin_panel', [
                        '_function' => 'order',
                        '_type' => 'display',
                        'id' => $entity->getId()
                    ]);
                $data['column'] = $column;
                break;
            case 'dateTimeOfOrder':
                {
                    $column['value'] = $entity->getDateTimeOfOrder()->format('d-m-Y');
                    $data['column'] = $column;
                }
                break;
        }

        $event->setData($data);

    }
}
