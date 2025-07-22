<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Factory\OrderHeaderFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixtureB
{


    private Proxy|null|OrderHeader $openOrderHeaderB = null;
    private Proxy|null|OrderHeader $inProcessOrderHeaderB = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeaderB = null;

    public function createOrderFixturesB(Proxy $customer): void
    {

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);

        $this->openOrderHeaderB = OrderHeaderFactory::createOne
        (
            ['customer' => $customer->object(),
                'orderStatusType' => $statusType->object()]
        );


        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);

        $this->inProcessOrderHeaderB = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $statusType->object()
            ]
        );

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);

        $this->afterPaymentSuccessOrderHeaderB = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $statusType->object()
            ]
        );



    }


}