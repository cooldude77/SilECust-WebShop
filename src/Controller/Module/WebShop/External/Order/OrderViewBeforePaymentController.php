<?php

namespace App\Controller\Module\WebShop\External\Order;

use App\Service\Module\WebShop\External\Order\OrderRead;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderViewBeforePaymentController extends AbstractController
{


    /**
     * @param OrderRead $orderRead
     *
     * @return Response
     *
     *  Create Order after checkout and before payment.
     *  Payment info to be added later
     */
    #[Route('/checkout/order/view', 'web_shop_view_order')]
    public function view(OrderRead $orderRead, CustomerFromUserFinder $customerFromUserFinder
    ): Response {

        $orderHeader = $orderRead->getOpenOrder($customerFromUserFinder->getLoggedInCustomer());

        return $this->render(
            'module/web_shop/external/order/order_view.html.twig',
            ['orderHeader' => $orderHeader]
        );

    }

}