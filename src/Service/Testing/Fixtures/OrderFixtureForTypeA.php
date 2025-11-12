<?php /** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */

namespace Silecust\WebShop\Service\Testing\Fixtures;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Factory\OrderHeaderFactory;
use Silecust\WebShop\Factory\OrderStatusFactory;
use Silecust\WebShop\Factory\OrderStatusTypeFactory;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Zenstruck\Foundry\Proxy;

trait OrderFixtureForTypeA
{


    private Proxy|null|OrderHeader $openOrderHeaderA = null;
    private Proxy|null|OrderHeader $inProcessOrderHeaderA = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeaderA = null;

    public function createOrderFixturesA(Proxy $customer): void
    {

        // status:created
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        $this->openOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );
        OrderStatusFactory::createOne(['orderHeader' => $this->openOrderHeaderA, 'orderStatusType' => $orderStatusType]);

        // need two status to reach in_process
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        $this->inProcessOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );
        // create status
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderA, 'orderStatusType' => $orderStatusType]);


        // After payment
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $this->afterPaymentSuccessOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );

        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA, 'orderStatusType' => $orderStatusType]);

    }


}