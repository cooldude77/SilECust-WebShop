<?php

namespace Silecust\WebShop\Controller\Admin\Customer\Framework\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends EnhancedAbstractController
{

    public function header() :Response {

        // for now common header
        return $this->render('@SilecustWebShop/admin/customer/dashboard/header.html.twig');


    }
}