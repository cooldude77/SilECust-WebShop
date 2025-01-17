<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\Panel\Components\PanelSideBarController;
use App\Controller\Component\UI\PanelMainController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
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
     * @param Request $request
     * @return Response
     */
    #[Route('/my/dashboard', name: 'my_dashboard')]
    #[Route('/my', name: 'my')]
    #[Route('/my/profile', name: 'my_profile')]
    #[Route('/my/orders', name: 'my_orders')]
    #[Route('/my/personal-info', name: 'my_personal_info')]
    #[Route('/my/addresses', name: 'my_addresses')]
    #[Route('/my/address/create', name: 'my_address_create')]
    #[Route('/my/orders/{id}/display', name: 'my_order_display')]
    #[Route('/my/orders/items/{id}/display', name: 'my_order_item_display')]
    public function dashboard(RouterInterface $router, Request $request): Response
    {
        $session = $request->getSession();

        $this->setSessionVariables($session);

        $matches = $router->matchRequest($request);

        switch ($matches['_route']) {

            case 'my':
            case 'my_dashboard':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'dashboard'
                );
                break;
            case 'my_profile':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'profile'
                );
                break;
            case 'my_addresses':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addresses'
                );
                break;
            case 'my_address_create':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'addressCreate'
                );
                break;
            case 'my_orders':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orders'
                );
                break;
            case 'my_order_display':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orderDisplay'
                );
                break;
            case 'my_order_item_display':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orderItemDisplay'
                );
                break;

            case 'my_personal_info':
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
     *
     * @return void
     */
    public function setSessionVariables(SessionInterface $session
    ): void
    {

        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'my');

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


        $session->set(PanelMainController::BASE_TEMPLATE, 'base/admin_base_template.html.twig');
    }


}