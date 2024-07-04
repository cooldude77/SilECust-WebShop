<?php

namespace App\Controller\Admin\Customer\Framework;

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

/**
 * Controller is for showing panel to customers
 * The sidebar is created on basis of action list inside Sidebar panel
 */
class MainController extends AbstractController
{
    public function __construct(private readonly CustomerFromUserFinder $customerFromUserFinder)
    {
    }

    #[Route('/my/profile', name: 'my_profile_panel')]
    public function profile(Request $request): Response
    {
        $session = $request->getSession();
        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'my_profile_panel');

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



        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    #[Route('/my/addresses', name: 'my_address_list')]
    public function addresses(Request $request): Response
    {
        $customer = $this->customerFromUserFinder->getLoggedInCustomer();
        return $this->forward(CustomerAddressController::class . '::list', [
            'id' => $customer->getId(),
            'request' => $request]);
    }

    #[Route('/my/orders', name: 'my_orders_list')]
    public function orders(Request $request): Response
    {
        return new Response("Hello");
    }

    #[Route('/my/personal-data', name: 'my_personal_data')]
    public function personal(Request $request): Response
    {
        return new Response("Hello");
    }


}