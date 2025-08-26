<?php

namespace Silecust\WebShop\Controller\Admin\Customer\Framework;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Admin\Customer\Framework\Components\ContentController;
use Silecust\WebShop\Controller\Admin\Customer\Framework\Components\FooterController;
use Silecust\WebShop\Controller\Admin\Customer\Framework\Components\HeadController;
use Silecust\WebShop\Controller\Admin\Customer\Framework\Components\HeaderController;
use Silecust\WebShop\Controller\Admin\Customer\Framework\Components\SideBarController;
use Silecust\WebShop\Event\Admin\Customer\Framework\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelFooterController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Controller is for showing panel to customers
 * The sidebar is created on basis of action list inside Sidebar panel
 */
class MainController extends EnhancedAbstractController
{

    /**
     * @param RouterInterface $router
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param Request $request
     * @return Response
     */
    #[Route('/my/dashboard', name: 'sc_my_dashboard')]
    #[Route('/my', name: 'sc_my')]
    #[Route('/my/profile', name: 'sc_my_profile')]
    #[Route('/my/orders', name: 'sc_my_orders')]
    #[Route('/my/personal-info', name: 'sc_my_personal_info')]
    #[Route('/my/addresses', name: 'sc_my_addresses')]
    #[Route('/my/address/create', name: 'sc_my_address_create')]
    #[Route('/my/address/{id}/edit', name: 'sc_my_address_edit')]
    #[Route('/my/address/{id}/display', name: 'sc_my_address_display')]
    #[Route('/my/address/{id}/delete', name: 'sc_my_address_delete')]
    #[Route('/my/orders/{generatedId}/display', name: 'sc_my_order_display')]
    #[Route('/my/orders/items/{id}/display', name: 'sc_my_order_item_display')]
    public function dashboard(RouterInterface $router, EventDispatcherInterface $eventDispatcher, Request $request): Response
    {
        $session = $request->getSession();

        $this->setSessionVariables($session, $eventDispatcher, $request);

        $matches = $router->matchRequest($request);

        switch ($matches['_route']) {

            case 'sc_my':
            case 'sc_my_dashboard':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'dashboard'
                );
                break;
            case 'sc_my_profile':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'profile'
                );
                break;
            case 'sc_my_addresses':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addresses'
                );
                break;
            case 'sc_my_address_create':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addressCreate'
                );
                break;
            case 'sc_my_address_display':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addressDisplay'
                );
                break;
            case 'sc_my_address_edit':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addressEdit'
                );
                break;
            case 'sc_my_address_delete':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addressDelete'
                );
                break;
            case 'sc_my_orders':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orders'
                );
                break;
            case 'sc_my_order_display':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orderDisplay'
                );
                break;
            case 'sc_my_order_item_display':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orderItemDisplay'
                );
                break;

            case 'sc_my_personal_info':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'personalInfo'
                );
                break;
        }

        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    /**
     * @param SessionInterface $session
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    public function setSessionVariables(SessionInterface         $session,
                                        EventDispatcherInterface $eventDispatcher,
                                        Request                  $request
    ): void
    {

        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'sc_my');

        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, ContentController::class
        );


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

        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request),
            PreHeadForwardingEvent::EVENT_NAME
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
    }


}