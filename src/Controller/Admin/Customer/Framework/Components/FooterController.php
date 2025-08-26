<?php

namespace Silecust\WebShop\Controller\Admin\Customer\Framework\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends EnhancedAbstractController
{

    public function footer(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/admin/ui/panel/footer/footer.html.twig'
        );


    }

}