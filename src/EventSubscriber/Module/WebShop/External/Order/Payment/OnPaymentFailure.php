<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Entity\OrderHeader;
use App\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use App\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentFailure implements EventSubscriberInterface
{
    private OrderHeader $orderHeader;

    public function __construct(private readonly OrderRead              $orderRead,
                                private readonly CustomerFromUserFinder $customerFromUserFinder,
                                private readonly OrderSave              $orderSave)
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentFailureEvent::AFTER_PAYMENT_FAILURE => ['afterPaymentFailure', 100]
        ];

    }

    public function afterPaymentFailure(PaymentFailureEvent $paymentEvent): void
    {

        $orderHeader = $paymentEvent->getOrderHeader();
        $this->orderSave->setOrderStatus($orderHeader, OrderStatusTypes::ORDER_PAYMENT_FAILED);

        $this->orderSave->savePayment($orderHeader, $paymentEvent->getPaymentFailureArray());

    }
}