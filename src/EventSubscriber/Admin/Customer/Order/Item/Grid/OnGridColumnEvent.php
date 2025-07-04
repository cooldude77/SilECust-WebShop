<?php

namespace Silecust\WebShop\EventSubscriber\Admin\Customer\Order\Item\Grid;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridColumnEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\Transaction\Order\Price\Item\ItemPriceCalculator;
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
            GridColumnEvent::EVENT_NAME => 'beforeDisplay'
        ];

    }

    /**
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function beforeDisplay(GridColumnEvent $event): void
    {
        $route = $this->router->match($event->getData()['request']->getPathInfo());
        if (!in_array($route['_route'], ['sc_my_order_display', 'sc_admin_route_order_display']))
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
                if ($route['_route'] == 'sc_my_order_display')
                    $column['value'] = $this->router->generate('sc_my_order_item_display', ['id' => $entity->getId()]);
                else
                    $column['value'] = $this->router->generate('sc_admin_panel', [
                        '_function' => 'order_item',
                        '_type' => 'display',
                        'id' => $entity->getId()]);

                break;
            case 'price':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getBasePriceAmount();
                break;

            case 'discount':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getDiscountAmount();
                break;
            case 'tax':
                $column['value'] = $this->itemPriceCalculator->getPriceObject($entity)->getTaxRatePercentage();
                break;
            case 'finalAmount':
                $column['value'] = $this->itemPriceCalculator->getPriceWithTax($entity) * $entity->getQuantity();
                break;
        }

        $data['column'] = $column;

        $event->setData($data);

    }

}