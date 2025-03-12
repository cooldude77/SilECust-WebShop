<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Shop\Components\FooterController;
use Silecust\WebShop\Controller\Module\WebShop\External\Shop\Components\HeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelFooterController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends EnhancedAbstractController
{

    #[Route('/admin', name: 'admin_panel')]
    public function admin(Request $request
    ): Response {


        $session = $request->getSession();

        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'admin_panel');

        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_NAME, SideBarController::class
        );
        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME,
            'sideBar'
        );

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );

        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, ContentController::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'content'
        );

        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_NAME, HeadController::class
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_METHOD_NAME,
            'head'
        );


        $session->set(
            PanelFooterController::FOOTER_CONTROLLER_CLASS_NAME, FooterController::class
        );
        $session->set(
            PanelFooterController::FOOTER_CONTROLLER_CLASS_METHOD_NAME,
            'footer'
        );

        $session->set(PanelMainController::BASE_TEMPLATE, '@SilecustWebShop/admin/base/admin_base.html.twig');


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

}