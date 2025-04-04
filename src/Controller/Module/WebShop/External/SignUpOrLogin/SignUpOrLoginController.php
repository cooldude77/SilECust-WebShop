<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\SignUpOrLogin;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpOrLoginController extends EnhancedAbstractController
{

    #[Route('/checkout/entry', name: 'sc_web_shop_sign_up_or_login')]

    public function SignUpOrLogin(): Response
    {
        return $this->render(
            '@SilecustWebShop/module/web_shop/external/checkout/page/checkout_user_login_sign_up_options_page.html.twig'
        );
    }
}