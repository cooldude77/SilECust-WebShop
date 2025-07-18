<?php

namespace Silecust\WebShop\EventSubscriber\Module\WebShop\External\Order\Cart;

use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class OnPaymentSuccess implements EventSubscriberInterface
{
    public function __construct(
        private CartProductManager $cartSessionProductService)
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaymentSuccessEvent::EVENT_NAME => ['afterPaymentSuccess', -1]
        ];

    }

    public function afterPaymentSuccess(PaymentSuccessEvent $paymentEvent): void
    {

        $this->cartSessionProductService->clearCart();
    }
}