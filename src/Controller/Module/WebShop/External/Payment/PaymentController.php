<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Payment;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\OrderPayment;
use Silecust\WebShop\Event\Component\UI\Twig\BeforeTwigRenderInController;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentFailureEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentStartEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentSuccessEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Payment\PaymentValidationEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound;
use Silecust\WebShop\Exception\Module\WebShop\External\Payment\PaymentInfoNotFound;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Silecust\WebShop\Service\Transaction\Order\Price\Header\HeaderPriceCalculator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends EnhancedAbstractController
{

    /**
     * @param string $generatedId
     * @param EventDispatcherInterface $eventDispatcher
     * @param OrderRead $orderRead
     * @param Request $request
     * @param HeaderPriceCalculator $headerPriceCalculator
     * @return Response
     * @throws PriceProductBaseNotFound
     * @throws PriceProductTaxNotFound
     */
    #[Route('/payment/order/{generatedId}/start', name: 'sc_web_shop_payment_start')]
    public function startPayment(
        string                   $generatedId,
        EventDispatcherInterface $eventDispatcher,
        OrderRead                $orderRead,
        Request                  $request,
        HeaderPriceCalculator    $headerPriceCalculator): Response
    {

        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);

        // set your id for validation later in this event
        $event = new PaymentStartEvent($orderHeader);
        $eventDispatcher->dispatch($event, PaymentStartEvent::BEFORE_PAYMENT_PROCESS);

        //todo: order object validator
        $finalPrice = $headerPriceCalculator->calculateOrderValue($orderHeader);

        //todo: tests pending for events
        $event = new BeforeTwigRenderInController($request);
        $eventDispatcher->dispatch($event, BeforeTwigRenderInController::BEFORE_TWIG_RENDER);

        return $event->getView() == null ?
            $this->render('@SilecustWebShop/module/web_shop/external/payment/start.html.twig',
                ['finalPrice' => $finalPrice, 'orderHeader' => $orderHeader]) :
            $this->render($event->getView());

    }


    /**
     * @throws PaymentInfoNotFound
     */
    #[Route('/payment/order/{generatedId}/success', name: 'sc_web_shop_payment_success')]
    public function onPaymentSuccess(
        EventDispatcherInterface $eventDispatcher,
        string                   $generatedId,
        OrderRead                $orderRead,
        Request                  $request,
    ): Response
    {


        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);

        // This method should throw exception in case payment details are not validated
        // The custom event handler must issue stopPropagation as early as possible
        $event = new PaymentValidationEvent($orderHeader, $request);
        $eventDispatcher->dispatch($event, PaymentValidationEvent::ON_PAYMENT_VALIDATION);

        if ($event->isPropagationStopped()) {
            return new Response('There was an error in payment', 403);
        } else {

            $event = new PaymentSuccessEvent($request);
            $eventDispatcher->dispatch($event, PaymentSuccessEvent::EVENT_NAME);

            $this->addFlash('success', 'Your payment was successful');
            $this->addFlash('success', 'Your order was created and is in under process');

            return $this->redirectToRoute('sc_module_web_shop_order_complete_details',
                ['generatedId' => $orderHeader->getGeneratedId()]);
        }
    }

    /**
     * @param string $generatedId
     * @param OrderRead $orderRead
     * @param EventDispatcherInterface $eventDispatcher
     * @param Request $request
     * @return Response
     *
     * // This method should throw exception in case payment details are not validated
     * // The custom event handler must issue stopPropagation as early as possible
     */
    #[Route('/payment/order/{generatedId}/failure', 'web_shop_payment_failure')]
    public function onPaymentFailure(
        string                   $generatedId,
        OrderRead                $orderRead,
        EventDispatcherInterface $eventDispatcher,
        Request                  $request,
    ): Response
    {

        $orderHeader = $orderRead->getOrderByGeneratedId($generatedId);

        // This method should throw exception in case payment details are not validated
        // The custom event handler must issue stopPropagation as early as possible
        $event = new PaymentFailureEvent($request);
        $eventDispatcher->dispatch($event, PaymentFailureEvent::AFTER_PAYMENT_FAILURE);

        if (!$event->isPropagationStopped()) {

            $this->addFlash('success', 'Your payment was successful');
            $this->addFlash('success', 'Your order was created and is in under process');

            return $this->render(
                '@SilecustWebShop/module/web_shop/external/payment/failure.html.twig',
                ['orderHeader' => $orderHeader]
            );
        } else {
            return new Response('There was an error in payment', 403);
        }
    }
}