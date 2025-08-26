<?php

namespace Silecust\WebShop\Service\Transaction\Admin\Order\Header\Display;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayFieldValueEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\Transaction\Order\Price\Header\HeaderPriceCalculator;

readonly class OrderHeaderFieldDisplayMapper
{

    public function __construct(
        private HeaderPriceCalculator $headerPriceCalculator)
    {
    }

    /**
     * @param DisplayFieldValueEvent $event
     * @param string $route1
     * @return mixed
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function mapData(DisplayFieldValueEvent $event, string $route1): mixed
    {
        $data = $event->getData();
        $column = $event->getData()['column'];
        /** @var OrderHeader $entity */
        $entity = $event->getData()['entity'];


        switch ($column['propertyName']) {

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
                $column['value'] = $this->headerPriceCalculator->calculateOrderValue($entity);
                $data['column'] = $column;
                break;
            default:
                $column['value'] = "";
                $data['column'] = $column;

        }
        return $data;
    }
}