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

trait OrderFixtureForTypeB
{


    private Proxy|null|OrderHeader $openOrderHeaderB = null;
    private Proxy|null|OrderHeader $inProcessOrderHeaderB = null;
    private Proxy|null|OrderHeader $afterPaymentSuccessOrderHeaderB = null;

    public function createOrderFixturesB(Proxy $customer): void
    {

        // Create Open Order
        // status:created
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        $this->openOrderHeaderB = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );
        OrderStatusFactory::createOne(['orderHeader' => $this->openOrderHeaderB, 'orderStatusType' => $orderStatusType]);

        // Create In Process Order
        // need two status to reach in_process
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        $this->inProcessOrderHeaderB = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );
        // create status
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderB, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderB, 'orderStatusType' => $orderStatusType]);

        // Create Payment complete Order
        // payment
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        $this->afterPaymentSuccessOrderHeaderB = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );

        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderB, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderB, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderB, 'orderStatusType' => $orderStatusType]);

    }


}