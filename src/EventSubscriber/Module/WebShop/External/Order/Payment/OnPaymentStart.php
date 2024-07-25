<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Entity\OrderItem;
use App\Event\Module\WebShop\External\Payment\PaymentEvent;
use App\Event\Module\WebShop\External\Payment\Types\PaymentEventTypes;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\MasterData\Pricing\PriceByCountryCalculator;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnPaymentStart implements EventSubscriberInterface
{

    public function __construct(private OrderRead $orderRead,
        private OrderSave $orderSave,
        private PriceByCountryCalculator $priceByCountryCalculator
    ) {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEventTypes::BEFORE_PAYMENT_PROCESS => 'beforePaymentFailure'
        ];

    }

    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function beforePaymentFailure(PaymentEvent $paymentEvent): void
    {

        $orderHeader = $this->orderRead->getOpenOrder($paymentEvent->getCustomer());
        $orderItems = $this->orderRead->getOrderItems($orderHeader);

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            $priceObject = $this->priceByCountryCalculator->getPriceObject($orderItem);
            $this->orderSave->savePrice($orderItem,$priceObject);
        }

    }
}