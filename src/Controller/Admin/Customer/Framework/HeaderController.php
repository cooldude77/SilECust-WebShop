<?php

namespace App\Controller\Admin\Customer\Framework;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends EnhancedAbstractController
{

    public function header() :Response {

        // for now common header
        return $this->render('admin/customer/dashboard/header.html.twig');


    }
}