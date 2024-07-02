<?php

namespace App\Controller\Admin\Customer\Framework;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends AbstractController
{

    public function header() :Response {

        // for now common header
        return $this->render('admin/ui/panel/header/header.html.twig');


    }
}