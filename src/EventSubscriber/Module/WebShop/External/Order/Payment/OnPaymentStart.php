<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Entity\OrderItem;
use App\Event\Module\WebShop\External\Payment\PaymentStartEvent;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Service\MasterData\Price\PriceByCountryCalculator;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnPaymentStart implements EventSubscriberInterface
{

    public function __construct(private readonly OrderRead                $orderRead,
                                private readonly OrderSave                $orderSave,
                                private readonly PriceByCountryCalculator $priceByCountryCalculator
    )
    {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentStartEvent::BEFORE_PAYMENT_PROCESS => 'beforePaymentStart'
        ];

    }

    /**
     *
     * @param PaymentStartEvent $paymentEvent
     *
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function beforePaymentStart(PaymentStartEvent $paymentEvent): void
    {

        $orderItems = $this->orderRead->getOrderItems($paymentEvent->getOrderHeader());

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            $priceObject = $this->priceByCountryCalculator->getPriceObject($orderItem);
            $this->orderSave->savePrice($orderItem, $priceObject);
        }

    }
}