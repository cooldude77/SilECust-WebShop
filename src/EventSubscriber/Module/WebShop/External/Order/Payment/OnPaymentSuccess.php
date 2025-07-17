<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Payment;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentSuccessResponseResolverInterface;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(
        private PaymentSuccessResponseResolverInterface $paymentSuccessResponseResolver,
        private OrderSave                               $orderSave,
        private OrderRead                               $orderRead,
        private CustomerFromUserFinder                  $customerFromUserFinder)
     {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentSuccessEvent::EVENT_NAME => ['afterPaymentSuccess', 0]
        ];

    }

    /**
     * @throws \Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException
     * @throws \Silecust\WebShop\Exception\Security\User\UserNotLoggedInException
     */
    public function afterPaymentSuccess(PaymentSuccessEvent $paymentEvent): void
    {

        $orderHeader = $this->orderRead->getOpenOrder($this->customerFromUserFinder->getLoggedInCustomer());

        $this->orderSave->setOrderStatus($orderHeader, OrderStatusTypes::ORDER_PAYMENT_COMPLETE);

        if ($this->orderRead->getPayment($orderHeader) == null) {
            $this->orderSave->savePayment($orderHeader,
                $this->paymentSuccessResponseResolver->resolve($paymentEvent->getRequest()));
        }

    }
}