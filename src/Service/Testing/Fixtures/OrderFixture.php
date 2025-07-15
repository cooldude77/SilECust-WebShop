<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Factory\OrderHeaderFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixture
{


    private Proxy|null|OrderHeader $openOrderHeader = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeader = null;

    public function createOrderFixtures(Proxy $customer): void
    {

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);

        $this->openOrderHeader = OrderHeaderFactory::createOne
        (
            ['customer' => $customer->object(),
                'orderStatusType' => $statusType->object()]
        );


        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);

        $this->afterPaymentSuccessOrderHeader = OrderHeaderFactory::createOne
        (
            ['customer' => $customer->object(),
                'orderStatusType' => $statusType->object()]
        );

    }


}