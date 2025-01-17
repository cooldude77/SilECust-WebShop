<?php

namespace App\Controller\Module\WebShop\External\Shop;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends EnhancedAbstractController
{

    public function header(Request $request): Response
    {
        return $this->render(
            'module/web_shop/external/base/web_shop_footer.html.twig'
        );


    }

}