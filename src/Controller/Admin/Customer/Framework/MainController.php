<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\Panel\Components\PanelSideBarController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\MasterData\Customer\Address\CustomerAddressController;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Controller is for showing panel to customers
 * The sidebar is created on basis of action list inside Sidebar panel
 */
class MainController extends AbstractController
{

    #[Route('/my/dashboard', name: 'my_dashboard')]
    #[Route('/my', name: 'my')]
    #[Route('/my/profile', name: 'my_profile')]
    #[Route('/my/orders', name: 'my_orders')]
    #[Route('/my/addresses', name: 'my_addresses')]
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
            case 'my_orders':
                $session->set(
                    PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
                    'orders'
                );
                break;
        }

        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return void
     */
    public function setSessionVariables(\Symfony\Component\HttpFoundation\Session\SessionInterface $session
    ): void {

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