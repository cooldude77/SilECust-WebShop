<?php

namespace App\Tests\Fixtures;

use App\Entity\OrderHeader;
use App\Factory\OrderHeaderFactory;
use App\Factory\OrderStatusTypeFactory;
use App\Service\Module\WebShop\External\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixture
{

    private Proxy|null|OrderHeader $orderHeader = null;

    public function createOpenOrder(Proxy $customer): void
    {
        OrderHeaderFactory::truncate();

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);

        $this->orderHeader = OrderHeaderFactory::createOne
        (
            ['customer' => $customer->object(),
             'orderStatusType' => $statusType->object()]
        );

    }


}