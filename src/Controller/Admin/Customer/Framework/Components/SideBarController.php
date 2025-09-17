<?php

namespace Silecust\WebShop\Controller\Admin\Customer\Framework\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Admin\SideBar\Role\SideBarRoleBasedListFilter;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SideBarController extends EnhancedAbstractController
{

    public function sideBar(SideBarRoleBasedListFilter $roleBasedSideBarList,
                            CustomerFromUserFinder     $customerFromUserFinder,
                            SessionInterface           $session
    ): Response
    {

        try {
            $customerFromUserFinder->getLoggedInCustomer();

            $sideBar = $roleBasedSideBarList->getListBasedOnRole(
                $this->generateUrl(
                    $session->get(PanelMainController::CONTEXT_ROUTE_SESSION_KEY))
            );

            return $this->render(
                '@SilecustWebShop/admin/ui/panel/sidebar/sidebar.html.twig', ['sideBar' => $sideBar]
            );
        } catch (UserNotLoggedInException|UserNotAssociatedWithACustomerException $e) {
            return new Response("Not Authorized", 403);
        }
    }
}