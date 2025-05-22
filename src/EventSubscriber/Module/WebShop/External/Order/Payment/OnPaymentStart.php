<?php /** @noinspection PhpUnused */

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Payment;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentStartEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\OrderSave;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentStart implements EventSubscriberInterface
{

    public function __construct(private OrderRead                $orderRead,
                                private OrderSave                $orderSave,
                                private PriceByCountryCalculator $priceByCountryCalculator
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
     * @noinspection PhpUnused
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