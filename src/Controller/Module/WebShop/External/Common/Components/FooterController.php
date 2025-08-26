<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Common\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends EnhancedAbstractController
{

    public function footer(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/module/web_shop/external/base/footer.html.twig'
        );


    }

}