<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Event\Admin\Employee\FrameWork\PreHeadForwardingEvent;
use App\Service\Component\UI\Panel\Components\PanelContentController;
use App\Service\Component\UI\Panel\Components\PanelHeaderController;
use App\Service\Component\UI\Panel\Components\PanelSideBarController;
use App\Service\Component\UI\Panel\PanelMainController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends EnhancedAbstractController
{

    #[Route('/admin', name: 'admin_panel')]
    public function admin(Request $request,
        EventDispatcherInterface $eventDispatcher
    ): Response {

        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request, $request->getSession()),
            PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT
        );


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

        $session->set(PanelMainController::BASE_TEMPLATE, 'base/admin_base_template.html.twig');


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

}