<?php

namespace Silecust\WebShop\Controller\Security\External\Credentials;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends EnhancedAbstractController
{

    #[Route(path: '/user/password/change', name: 'user_change_password')]
    public function changePassword(): Response
    {
        // todo
        return $this->render('security/user/profile/profile_page.html.twig');
    }

}