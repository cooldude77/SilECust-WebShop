<?php

namespace App\Tests\Fixtures;

use App\Entity\OrderHeader;
use App\Factory\OrderHeaderFactory;
use App\Factory\OrderStatusTypeFactory;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixture
{


    private Proxy|null|OrderHeader $openOrderHeader = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeader = null;

    public function createOpenOrderFixtures(Proxy $customer): void
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