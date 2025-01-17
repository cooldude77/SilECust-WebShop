<?php

namespace App\Controller\Component\UI\Panel\Components;

use App\Service\Component\UI\Panel\SessionAndMethodChecker;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelHeadController extends EnhancedAbstractController
{
    public const string HEAD_CONTROLLER_CLASS_NAME = 'HEAD_CONTROLLER_CLASS_NAME';
    public const string HEAD_CONTROLLER_CLASS_METHOD_NAME = 'HEAD_CONTROLLER_CLASS_METHOD_NAME';

    public const string PAGE_TITLE = "pageTitle";

    public function __construct(private readonly SessionAndMethodChecker $sessionAndMethodChecker)
    {
    }

    public function head(Request $request, SessionInterface $session): Response
    {


        if (
            $this->sessionAndMethodChecker->checkSessionVariablesAndMethod(
                self::HEAD_CONTROLLER_CLASS_NAME,
                self::HEAD_CONTROLLER_CLASS_METHOD_NAME
            )
        ) {

            $response = $this->forward(
                $session->get(self::HEAD_CONTROLLER_CLASS_NAME)
                . "::"
                . $session->get(self::HEAD_CONTROLLER_CLASS_METHOD_NAME),
                ['request' => $request]
            );
            // clear session variables after content has been retrieved
            $session->set(self::HEAD_CONTROLLER_CLASS_NAME, null);
            $session->set(self::HEAD_CONTROLLER_CLASS_METHOD_NAME, null);

        }
        /*
        else {


            $response = $this->render(
                'admin/ui/panel/head/head.html.twig',
                ['page_title' => $session->get(PanelHeadController::PAGE_TITLE)]
            );
        }
        */
        else {
            $response = new Response();
        }

        return $response;
    }


}