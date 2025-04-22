<?php

namespace Silecust\WebShop\EventSubscriber\Transaction\Admin\Order\Header\Display;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayFieldValueEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\Transaction\Order\Price\Header\HeaderPriceCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
readonly class OnFieldValueEvent implements EventSubscriberInterface
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
            DisplayFieldValueEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @param DisplayFieldValueEvent $event
     * @return void
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function beforeDisplay(DisplayFieldValueEvent $event): void
    {

        $route = $this->router->match($event->getData()['request']->getPathInfo());

        if (!in_array($route['_route'], ['sc_my_orders', 'sc_admin_route_order_list']))
            if (!($event->getData()['request']->query->get('_function') == 'order'
                && $event->getData()['request']->query->get('_type') == 'list')
            )
                return;

        $data = $event->getData();
        $column = $event->getData()['column'];
        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {
            case 'generatedId':
                if ($route['_route'] == 'sc_my_orders')
                    $column['value'] = $this->router->generate('sc_my_order_display', ['generatedId' => $entity->getGeneratedId()]);
                else
                    $column['value'] = $this->router->generate('sc_admin_panel', [
                        '_function' => 'order',
                        '_type' => 'display',
                        'generatedId' => $entity->getGeneratedId()
                    ]);
                $data['column'] = $column;
                break;
            case 'dateTimeOfOrder':
                {
                    $column['value'] = $entity->getDateTimeOfOrder()->format('d-m-Y');
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
            default:
                $column['value'] = "";
                $data['column'] = $column;

        }

        $event->setData($data);

    }
}
