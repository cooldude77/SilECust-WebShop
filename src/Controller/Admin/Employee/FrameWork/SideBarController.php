<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Controller\Component\UI\PanelMainController;
use App\Exception\Security\User\Employee\UserNotAssociatedWithAnEmployeeException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use App\Service\Security\User\Employee\EmployeeFromUserFinder;
 use App\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class SideBarController extends EnhancedAbstractController
{

    public function sideBar(RoleBasedSideBarList $roleBasedSideBarList,
        SessionInterface $session
    ): Response {
        try {
            $sideBar = $roleBasedSideBarList->getListBasedOnRole(
                $this->generateUrl(
                    $session
                        ->get(PanelMainController::CONTEXT_ROUTE_SESSION_KEY)
                )
            );


            return $this->render(
                'admin/ui/panel/sidebar/sidebar.html.twig', ['sideBar' => $sideBar]
            );
        } catch (UserNotLoggedInException|UserNotAssociatedWithAnEmployeeException$e) {
            return new Response("Not Authorized", 403);
        }
    }
}