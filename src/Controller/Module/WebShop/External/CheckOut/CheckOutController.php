<?php

namespace App\Controller\Module\WebShop\External\CheckOut;

use App\Controller\Component\Routing\RoutingConstants;
use App\Service\Module\WebShop\External\Address\CheckOutAddressQuery;
use App\Service\Module\WebShop\External\Address\CheckOutAddressService;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckOutController extends AbstractController
{


    #[Route('/checkout', name: 'web_shop_checkout')]
    public function checkout(
        CustomerFromUserFinder $customerFromUserFinder,
        CartSessionProductService $cartSessionService,
        CheckOutAddressQuery $checkOutAddressQuery
    ): Response {

        // The checkout page will display appropriate twig templates
        // after following processes, the control should come back to this method
        // and if everything is ok redirect to payment page

        if ($this->getUser() == null) {
            return $this->redirectToRoute('user_customer_sign_up', [
                RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                    'web_shop_checkout'
                )]);
        }

        //todo check if the user is a customer

        if (!$cartSessionService->isInitialized() || !$cartSessionService->hasItems()) {
            // todo: add flash
            return $this->redirectToRoute('module_web_shop_cart');
        }


        if (!$checkOutAddressQuery->isShippingAddressChosen()
            || !$checkOutAddressQuery->isBillingAddressChosen()) {
            return $this->redirectToRoute('web_shop_checkout_addresses');
        }


        return  $this->redirectToRoute('module_web_shop_payment');

    }
}