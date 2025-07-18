<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\CheckOut;

use Psr\EventDispatcher\EventDispatcherInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Module\WebShop\External\CheckOut\CheckoutProcessCompleteEvent;
use Silecust\WebShop\Service\Component\Routing\RoutingConstants;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressQuery;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckOutController extends EnhancedAbstractController
{


    #[Route('/checkout', name: 'sc_web_shop_checkout')]
    public function checkout(
        EventDispatcherInterface $eventDispatcher,
        CartProductManager       $cartSessionService,
        CheckOutAddressQuery     $checkOutAddressQuery
    ): Response
    {

        // The checkout page will display appropriate twig templates
        // after following processes, the control should come back to this method
        // and if everything is ok redirect to payment page

        if ($this->getUser() == null) {
            return $this->redirectToRoute(
                'sc_user_customer_sign_up',
                [RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                    'sc_web_shop_checkout'
                )]
            );
        }

        //todo check if the user is a customer

        if (!$cartSessionService->isInitialized() || !$cartSessionService->hasItems()) {
            // todo: add flash
            return $this->redirectToRoute('sc_module_web_shop_cart');
        }


        if (!$checkOutAddressQuery->isShippingAddressChosen()
            || !$checkOutAddressQuery->isBillingAddressChosen()
        ) {
            return $this->redirectToRoute('sc_web_shop_checkout_addresses');
        }


        $eventDispatcher->dispatch(new CheckoutProcessCompleteEvent());

        return $this->redirectToRoute('sc_web_shop_view_order');

    }
}