<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Shop\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends EnhancedAbstractController
{

    public function head(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/module/web_shop/external/base/head.html.twig',

        );


    }

}