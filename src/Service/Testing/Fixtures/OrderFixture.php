<?php

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Factory\OrderHeaderFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixture
{


    private Proxy|null|OrderHeader $openOrderHeaderA = null;
    private Proxy|null|OrderHeader $inProcessOrderHeaderA = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeaderA = null;

    public function createOrderFixturesA(Proxy $customer): void
    {

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);

        $this->openOrderHeaderA = OrderHeaderFactory::createOne
        (
            ['customer' => $customer->object(),
                'orderStatusType' => $statusType->object()]
        );


        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);

        $this->inProcessOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $statusType->object()
            ]
        );

        $statusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);

        $this->afterPaymentSuccessOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $statusType->object()
            ]
        );



    }


}