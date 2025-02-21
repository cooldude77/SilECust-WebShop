<?php

namespace Silecust\WebShop\Controller\Admin\Employee\FrameWork;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends EnhancedAbstractController
{

    public function header(): Response
    {

        // for now common header
        return $this->render('admin/employee/dashboard/header.html.twig');


    }
}