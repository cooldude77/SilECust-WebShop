<?php

namespace App\EventSubscriber\Module\WebShop\External\Order\Payment;

use App\Entity\OrderItem;
use App\Event\Module\WebShop\External\Payment\PaymentEvent;
use App\Event\Module\WebShop\External\Payment\Types\PaymentEventTypes;
use App\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use App\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\MasterData\Pricing\PriceByCountryCalculator;
use App\Service\Transaction\Order\OrderRead;
use App\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnPaymentStart implements EventSubscriberInterface
{

    public function __construct(private readonly OrderRead $orderRead,
        private readonly OrderSave $orderSave,
        private readonly PriceByCountryCalculator $priceByCountryCalculator
    ) {
        //todo: add snapshot
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEventTypes::BEFORE_PAYMENT_PROCESS => 'beforePaymentStart'
        ];

    }

    /**
     *
     * @param PaymentEvent $paymentEvent
     *
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    public function beforePaymentStart(PaymentEvent $paymentEvent): void
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