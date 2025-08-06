<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Customer;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(
        private OrderRead              $orderRead,
        private CustomerFromUserFinder $customerFromUserFinder,
        private SerializerInterface    $serializer)
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentSuccessEvent::EVENT_NAME => ['afterPaymentSuccess',2]
        ];

    }

    /**
     * @throws \Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException
     * @throws \Silecust\WebShop\Exception\Security\User\UserNotLoggedInException
     */
    public function afterPaymentSuccess(PaymentSuccessEvent $paymentEvent): void
    {

        $orderHeader = $this->orderRead->getOpenOrder($this->customerFromUserFinder->getLoggedInCustomer());

        $orderHeader->setCustomerInJson($this->serializer->serialize($orderHeader->getCustomer(),'json'));

    }
}