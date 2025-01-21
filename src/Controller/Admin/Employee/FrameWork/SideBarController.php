<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Exception\Security\User\Employee\UserNotAssociatedWithAnEmployeeException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use App\Service\Component\UI\Panel\PanelMainController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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