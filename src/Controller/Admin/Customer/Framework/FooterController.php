<?php

namespace App\Controller\Admin\Customer\Framework;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends EnhancedAbstractController
{

    public function header(Request $request): Response
    {
        return $this->render(
            'admin/ui/panel/footer/footer.html.twig'
        );


    }

}