<?php

namespace App\Controller\Module\WebShop\External\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends AbstractController
{

    public function header(Request $request): Response
    {
        return $this->render(
            'module/web_shop/external/base/web_shop_footer.html.twig'
        );


    }

}