<?php

namespace App\Controller\Module\WebShop\External\Payment;

use App\Event\Module\WebShop\External\Payment\PaymentEvent;
use App\Event\Module\WebShop\External\Payment\Types\PaymentEventTypes;
use App\Service\Module\WebShop\External\Order\OrderRead;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckOutPaymentController extends AbstractController
{

    #[Route('/checkout/payment/start', 'web_shop_payment_start')]
    public function startPayment(EventDispatcherInterface $eventDispatcher): Response
    {
        $eventDispatcher->dispatch(new PaymentEvent(), PaymentEventTypes::BEFORE_PAYMENT_PROCESS);

        return $this->render(
            'module/web_shop/external/payment/start.html.twig'
        );
    }


    #[Route('/checkout/payment/success', 'web_shop_payment_success')]
    public function onPaymentSuccess(EventDispatcherInterface $eventDispatcher,
        OrderRead $orderRead,
        CustomerFromUserFinder $customerFromUserFinder,
        Request $request
    ): RedirectResponse {

        $orderHeader = $orderRead->getOpenOrder($customerFromUserFinder->getLoggedInCustomer());

        $event = new PaymentEvent();
        $event->setCustomer($customerFromUserFinder->getLoggedInCustomer());
        $eventDispatcher->dispatch($event, PaymentEventTypes::AFTER_PAYMENT_SUCCESS);

        $this->addFlash('success','Your payment was successful');

        $this->addFlash('success','Your order was created and is in under process');

        return  $this->redirectToRoute('module_web_shop_order_complete_details',
            ['id'=>$orderHeader->getId()]);

    }

    #[Route('/checkout/payment/failure', 'web_shop_payment_failure')]
    public function onPaymentFailure(EventDispatcherInterface $eventDispatcher,
        OrderRead $orderRead,
        CustomerFromUserFinder $customerFromUserFinder,
        Request $request
    ): Response {
        $orderHeader = $orderRead->getOpenOrder($customerFromUserFinder->getLoggedInCustomer());

        $event = new PaymentEvent();

        $event->setCustomer($customerFromUserFinder->getLoggedInCustomer());

        $eventDispatcher->dispatch($event, PaymentEventTypes::AFTER_PAYMENT_FAILURE);

        return $this->render(
            'module/web_shop/external/payment/failure.html.twig',
            ['orderHeader' => $orderHeader]
        );
    }
}