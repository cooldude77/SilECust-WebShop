<?php

namespace App\Controller\Component\UI\Panel\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PanelFooterController extends AbstractController
{
    public const string FOOTER_CONTROLLER_CLASS_NAME = 'FOOTER_CONTROLLER_CLASS_NAME';
    public const string FOOTER_CONTROLLER_CLASS_METHOD_NAME = 'FOOTER_CONTROLLER_CLASS_METHOD_NAME';

    public function header(Request $request): Response
    {
        return $this->forward(
            self::FOOTER_CONTROLLER_CLASS_NAME . "::"
            . self::FOOTER_CONTROLLER_CLASS_METHOD_NAME, ['request' => $request]
        );
    }

}