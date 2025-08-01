<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Header\Grid;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Service\Transaction\Order\Price\Header\HeaderPriceCalculator;
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
    public function __construct(private RouterInterface                $router,
                                private readonly HeaderPriceCalculator $orderPriceValueCalculator)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GridColumnEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param GridColumnEvent $event
     * @return void
     */
    public function beforeDisplay(GridColumnEvent $event): void
    {

        $route = $this->router->match($event->getData()['request']->getPathInfo());

        if ($route['_route'] != 'sc_my_orders')
            return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'generatedId':
                $column['value'] = $this->router->generate('sc_my_order_display', ['generatedId' => $entity->getGeneratedId()]);
                $data['column'] = $column;
                break;
            case 'dateTimeOfOrder':
                {
                    $column['value'] = $entity->getDateTimeOfOrder()->format('d-m-Y H:i:s');
                    $data['column'] = $column;
                }
                break;
            case 'orderStatusType':
                $column['value'] = $entity->getOrderStatusType()->getDescription();
                $data['column'] = $column;
                break;
            case 'orderValue':
                $column['value'] = $this->orderPriceValueCalculator->calculateOrderValue($entity);
                $data['column'] = $column;
                break;

        }

        $event->setData($data);

    }
}
