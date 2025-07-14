<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Exception\Security\User\Employee\UserNotAssociatedWithAnEmployeeException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
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
                '@SilecustWebShop/admin/ui/panel/sidebar/sidebar.html.twig', ['sideBar' => $sideBar]
            );
        } catch (UserNotLoggedInException|UserNotAssociatedWithAnEmployeeException$e) {
            return new Response("Not Authorized", 403);
        }
    }
}