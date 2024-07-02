<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\Component\UI\PanelMainController;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SideBarController extends AbstractController
{

    public function sideBar(RoleBasedSideBarList $roleBasedSideBarList,
        CustomerFromUserFinder $customerFromUserFinder,
        SessionInterface $session
    ): Response {

        try {
            $customerFromUserFinder->getLoggedInCustomer();

            $role = 'ROLE_CUSTOMER';

            $sideBar = $roleBasedSideBarList->getListBasedOnRole(
                $role,
                $this->generateUrl(
                    $session
                        ->get(PanelMainController::CONTEXT_ROUTE_SESSION_KEY)
                )
            );

            return $this->render(
                'admin/ui/panel/sidebar/sidebar.html.twig', ['sideBar' => $sideBar]
            );
        } catch (UserNotLoggedInException $e) {
            return new Response("Not Authorized", 403);
        } catch (UserNotAssociatedWithACustomerException $e) {

            return new Response("Not Authorized", 403);
        }
    }
}