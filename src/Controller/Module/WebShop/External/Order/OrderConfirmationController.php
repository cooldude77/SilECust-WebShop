<?php

namespace App\Controller\Module\WebShop\External\Order;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
use App\Repository\OrderHeaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderConfirmationController extends AbstractController
{
    #[Route('/order/{id}/success', 'module_web_shop_order_complete_details')]
    public function view(Request $request,SessionInterface $session): Response
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


    public function thankYou(int $id, OrderHeaderRepository $orderHeaderRepository): Response
    {
        // todo: check referring route
        // this page will be displayed only when referred from payment

        $orderHeader = $orderHeaderRepository->find($id);

        return $this->render(
            'module/web_shop/external/order/thank_you_for_your_order.html.twig',
            ['orderHeader' => $orderHeader]
        );

    }
}