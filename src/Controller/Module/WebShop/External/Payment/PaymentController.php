<?php

namespace App\Controller\Module\WebShop\External\Payment;

use App\Entity\OrderPayment;
use App\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use App\Event\Module\WebShop\External\Payment\PaymentStartEvent;
use App\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Module\WebShop\External\Payment\PaymentPriceCalculator;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Transaction\Order\OrderRead;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{

    /**
     * @param string $generatedId
     * @param EventDispatcherInterface $eventDispatcher
     * @param OrderRead $orderRead
     * @param PaymentPriceCalculator $paymentPriceCalculator
     *
     * @return Response
     */
    #[Route('/payment/order/{generatedId}/start', 'web_shop_payment_start')]
    public function startPayment(
        string                   $generatedId,
        EventDispatcherInterface $eventDispatcher,
        OrderRead                $orderRead,
        PaymentPriceCalculator   $paymentPriceCalculator): Response
    {

        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);

        $event = new PaymentStartEvent($orderHeader);

        $eventDispatcher->dispatch($event, PaymentStartEvent::BEFORE_PAYMENT_PROCESS);

        $orderItemPaymentPrices = $orderRead->getOrderItemPaymentPrices($orderHeader);
        $finalPrice = $paymentPriceCalculator->calculateOrderPaymentPrice($orderItemPaymentPrices);

        return $this->render(
            'module/web_shop/external/payment/start.html.twig',
            ['finalPrice' => $finalPrice, 'orderHeader' => $orderHeader]
        );
    }


    #[Route('/payment/order/{generatedId}/success', 'web_shop_payment_success')]
    public function onPaymentSuccess(EventDispatcherInterface $eventDispatcher,
                                     string                   $generatedId,
                                     OrderRead                $orderRead,
                                     Request                  $request,
    ): RedirectResponse
    {
        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);

        $paymentInfo = $request->request->all()[OrderPayment::PAYMENT_GATEWAY_RESPONSE];

        $event = new PaymentSuccessEvent($orderHeader,$paymentInfo);

        $eventDispatcher->dispatch($event, PaymentSuccessEvent::AFTER_PAYMENT_SUCCESS);

        $this->addFlash('success', 'Your payment was successful');

        $this->addFlash('success', 'Your order was created and is in under process');

        return $this->redirectToRoute('module_web_shop_order_complete_details',
            ['generatedId' => $orderHeader->getGeneratedId()]);

    }

    #[Route('/payment/order/{generatedId}/failure', 'web_shop_payment_failure')]
    public function onPaymentFailure(
        string                   $generatedId,
        OrderRead                $orderRead,
        EventDispatcherInterface $eventDispatcher,
        Request                  $request,
    ): Response
    {

        $paymentInfo = $request->request->all()[OrderPayment::PAYMENT_GATEWAY_RESPONSE];
        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);


        $event = new PaymentFailureEvent($orderHeader,$paymentInfo);

        $eventDispatcher->dispatch($event, PaymentFailureEvent::AFTER_PAYMENT_FAILURE);

        return $this->render(
            'module/web_shop/external/payment/failure.html.twig',
            ['orderHeader' => $orderHeader]
        );
    }
}