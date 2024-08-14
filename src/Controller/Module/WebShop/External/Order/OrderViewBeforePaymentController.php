<?php

namespace App\Controller\Module\WebShop\External\Order;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Transaction\Order\OrderRead;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderViewBeforePaymentController extends AbstractController
{


    /**
     *
     * @param Request $request
     *
     * @return Response
     *
     *  Create Order after checkout and before payment.
     *  Payment info to be added later
     */
    #[Route('/checkout/order/view', 'web_shop_view_order')]
    public function view(Request $request): Response
    {


        $session = $request->getSession();

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'order'
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            'module/web_shop/external/order/page/order_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }


    /**
     * @param OrderRead              $orderRead
     * @param CustomerFromUserFinder $customerFromUserFinder
     *
     * @return Response
     * @throws UserNotLoggedInException
     * @throws UserNotAssociatedWithACustomerException
     */
    public function order(OrderRead $orderRead, CustomerFromUserFinder $customerFromUserFinder
    ): Response {

        $orderHeader = $orderRead->getOpenOrder($customerFromUserFinder->getLoggedInCustomer());
        $orderObject = $orderRead->getOrderObject($orderHeader);


        return $this->render(
            'module/web_shop/external/order/order_view.html.twig',
            ['orderObject' => $orderObject]
        );
    }

}