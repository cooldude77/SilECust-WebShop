<?php

namespace Silecust\WebShop\Controller\Admin\Customer\Framework;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends EnhancedAbstractController
{

    public function head(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/admin/ui/panel/head/head.html.twig',

        );


    }

}