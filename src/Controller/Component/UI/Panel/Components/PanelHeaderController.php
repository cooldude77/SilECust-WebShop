<?php

namespace App\Controller\Component\UI\Panel\Components;

use App\Service\Component\UI\Panel\SessionAndMethodChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelHeaderController extends AbstractController
{
    public const string HEADER_CONTROLLER_CLASS_NAME = 'HEADER_CONTROLLER_CLASS_NAME';
    public const string HEADER_CONTROLLER_CLASS_METHOD_NAME = 'HEADER_CONTROLLER_CLASS_METHOD_NAME';

    public const string IS_HEADER_VISIBLE = "IS_HEADER_VISIBLE";

    public function __construct(private readonly SessionAndMethodChecker $sessionAndMethodChecker)
    {
    }

    public function header(Request $request, SessionInterface $session): Response
    {


        if (
            $this->sessionAndMethodChecker->checkSessionVariablesAndMethod(
                self::HEADER_CONTROLLER_CLASS_NAME,
                self::HEADER_CONTROLLER_CLASS_METHOD_NAME
            )
        ) {

            $response = $this->forward(
                $session->get(self::HEADER_CONTROLLER_CLASS_NAME)
                . "::"
                . $session->get(self::HEADER_CONTROLLER_CLASS_METHOD_NAME),
                ['request' => $request]
            );
            // clear session variables after content has been retrieved
            $session->set(self::HEADER_CONTROLLER_CLASS_NAME, null);
            $session->set(self::HEADER_CONTROLLER_CLASS_METHOD_NAME, null);

        } else {
            $response = new Response();
        }

        return $response;
    }



}