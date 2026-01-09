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

    private Proxy|null|OrderHeader $afterPaymentFailureOrderHeaderA = null;

    public function createOrderFixturesA(Proxy $customer): void
    {

        // Create Open Order
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

        // Create In Process Order
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
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        OrderStatusFactory::createOne(['orderHeader' => $this->inProcessOrderHeaderA, 'orderStatusType' => $orderStatusType]);

        // Create Payment Failure Order
        // need two status to reach payment failure
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        $this->afterPaymentFailureOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );
        // create status
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentFailureOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_FAILED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentFailureOrderHeaderA, 'orderStatusType' => $orderStatusType]);

        // Create Payment complete Order
        // payment
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        $this->afterPaymentSuccessOrderHeaderA = OrderHeaderFactory::createOne
        (
            [
                'customer' => $customer->object(),
                'orderStatusType' => $orderStatusType->object()
            ]
        );

        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_CREATED]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_IN_PROCESS]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA, 'orderStatusType' => $orderStatusType]);
        $orderStatusType = OrderStatusTypeFactory::find(['type' => OrderStatusTypes::ORDER_PAYMENT_COMPLETE]);
        OrderStatusFactory::createOne(['orderHeader' => $this->afterPaymentSuccessOrderHeaderA, 'orderStatusType' => $orderStatusType]);

    }


}