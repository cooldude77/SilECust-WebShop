<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\Component\UI\PanelMainController;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SideBarController extends EnhancedAbstractController
{

    public function sideBar(RoleBasedSideBarList $roleBasedSideBarList,
        CustomerFromUserFinder $customerFromUserFinder,
        SessionInterface $session
    ): Response {

        try {
            $customerFromUserFinder->getLoggedInCustomer();

            $sideBar = $roleBasedSideBarList->getListBasedOnRole(
                $this->generateUrl(
                    $session->get(PanelMainController::CONTEXT_ROUTE_SESSION_KEY))
            );

            return $this->render(
                'admin/ui/panel/sidebar/sidebar.html.twig', ['sideBar' => $sideBar]
            );
        } catch (UserNotLoggedInException|UserNotAssociatedWithACustomerException $e) {
            return new Response("Not Authorized", 403);
        }
    }
}