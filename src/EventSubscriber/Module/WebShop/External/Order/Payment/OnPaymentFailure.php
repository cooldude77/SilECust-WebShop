<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\Resolver\PaymentFailureResponseResolverInterface;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentFailure implements EventSubscriberInterface
{

    public function __construct(
        private PaymentFailureResponseResolverInterface $paymentFailureResponseResolver,

        private OrderSave                               $orderSave,
        private OrderRead                               $orderRead,
        private CustomerFromUserFinder                  $customerFromUserFinder)
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentFailureEvent::AFTER_PAYMENT_FAILURE => ['afterPaymentFailure', 100]
        ];

    }

    /**
     * @param PaymentFailureEvent $paymentEvent
     * @return void
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function afterPaymentFailure(PaymentFailureEvent $paymentEvent): void
    {
        $orderHeader = $this->orderRead->getOpenOrder($this->customerFromUserFinder->getLoggedInCustomer());


        $this->orderSave->setOrderStatus($orderHeader, OrderStatusTypes::ORDER_PAYMENT_FAILED);

        $this->orderSave->savePayment(
            $orderHeader,
            $this->paymentFailureResponseResolver->resolve($paymentEvent->getRequest())
        );

    }
}