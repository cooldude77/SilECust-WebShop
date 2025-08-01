<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends EnhancedAbstractController
{

    public function head(Request $request): Response
    {
        return $this->render(
            '@SilecustWebShop/admin/employee/ui/panel/head/head.html.twig', ['request' => $request]);


    }

}