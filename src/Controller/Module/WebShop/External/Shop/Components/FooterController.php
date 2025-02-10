<?php

namespace App\Controller\Module\WebShop\External\Shop\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends EnhancedAbstractController
{

    public function header(Request $request): Response
    {
        return $this->render(
            'module/web_shop/external/base/footer.html.twig'
        );


    }

}