<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Order;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderConfirmationController extends EnhancedAbstractController
{
    #[Route('/order/{generatedId}/success', name: 'sc_module_web_shop_order_complete_details')]
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
            'thankYou'
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            '@SilecustWebShop/module/web_shop/external/order/page/order_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }


    public function thankYou(Request $request, OrderHeaderRepository $orderHeaderRepository): Response
    {
        // todo: check referring route
        // this page will be displayed only when referred from payment

        $orderHeader = $orderHeaderRepository->findOneBy(['generatedId'=>$request->query->get('generatedId')]);

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/order/thank_you_for_your_order.html.twig',
            ['orderHeader' => $orderHeader]
        );

    }
}