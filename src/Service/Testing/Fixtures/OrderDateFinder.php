<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Factory\OrderStatusFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderDateFinder
{

    /**
     * @param \Silecust\WebShop\Entity\OrderHeader $
     * @param $orderHeader
     * @return string
     * @throws \Exception
     */
    public function getOrderCreatedStatusDate(Proxy|OrderHeader $orderHeader)
    {
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        /** @var \Silecust\WebShop\Entity\OrderStatus $orderStatus */
        $orderStatus = OrderStatusFactory::find(['orderHeader' => $orderHeader, 'orderStatusType' => $orderStatusType]);

        /** @var \DateTimeImmutable $date */
        $date = $orderStatus->getStatusCreatedAt();
        $timeZone = new \DateTimeZone($orderStatus->getStatusCreatedAtTimeZone());
        $d = $date->setTimezone($timeZone);

        return $d->format('d-m-Y H:i:s');

    }
}