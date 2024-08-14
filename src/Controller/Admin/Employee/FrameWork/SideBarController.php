<?php

namespace App\Controller\Admin\Employee\FrameWork;

use App\Controller\Component\UI\PanelMainController;
use App\Service\Admin\SideBar\Role\RoleBasedSideBarList;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SideBarController extends AbstractController
{

    public function sideBar(RoleBasedSideBarList $roleBasedSideBarList,
        CustomerFromUserFinder $customerFromUserFinder,
        SessionInterface $session
    ) {

        $role = 'ROLE_EMPLOYEE';

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

    }
}