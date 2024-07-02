<?php

namespace App\Controller\Module\WebShop\External\SignUpOrLogin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpOrLoginController extends AbstractController
{

    #[Route('/checkout/entry', name: 'web_shop_sign_up_or_login')]

    public function SignUpOrLogin(): Response
    {
        return $this->render(
            'module/web_shop/external/checkout/page/checkout_user_login_sign_up_options_page.html.twig'
        );
    }
}