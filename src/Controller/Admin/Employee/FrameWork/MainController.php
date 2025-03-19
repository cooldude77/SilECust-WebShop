<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\FooterController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelFooterController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends EnhancedAbstractController
{

    #[Route('/admin', name: 'sc_admin_panel')]
    public function admin(Request $request,
        EventDispatcherInterface $eventDispatcher
    ): Response {

        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request),
            PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT
        );


        $session = $request->getSession();

        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'sc_admin_panel');

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