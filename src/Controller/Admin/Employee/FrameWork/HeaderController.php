<?php

namespace App\Controller\Admin\Employee\FrameWork;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends AbstractController
{

    public function header(): Response
    {

        // for now common header
        return $this->render('admin/employee/dashboard/header.html.twig');


    }
}