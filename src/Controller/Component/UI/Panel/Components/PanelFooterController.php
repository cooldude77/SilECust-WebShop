<?php

namespace App\Controller\Component\UI\Panel\Components;

use App\Service\Component\UI\Panel\SessionAndMethodChecker;
 use App\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelFooterController extends EnhancedAbstractController
{
    public const string FOOTER_CONTROLLER_CLASS_NAME = 'FOOTER_CONTROLLER_CLASS_NAME';
    public const string FOOTER_CONTROLLER_CLASS_METHOD_NAME = 'FOOTER_CONTROLLER_CLASS_METHOD_NAME';

    public function __construct(private readonly SessionAndMethodChecker $sessionAndMethodChecker)
    {
    }

    public function footer(Request $request, SessionInterface $session): Response
    {
        if (
            $this->sessionAndMethodChecker->checkSessionVariablesAndMethod(
                self::FOOTER_CONTROLLER_CLASS_NAME,
                self::FOOTER_CONTROLLER_CLASS_METHOD_NAME
            )
        ) {

            $response = $this->forward(
                $session->get(self::FOOTER_CONTROLLER_CLASS_NAME)
                . "::"
                . $session->get(self::FOOTER_CONTROLLER_CLASS_METHOD_NAME),
                ['request' => $request]
            );
            // clear session variables after content has been retrieved
            $session->set(self::FOOTER_CONTROLLER_CLASS_NAME, null);
            $session->set(self::FOOTER_CONTROLLER_CLASS_METHOD_NAME, null);

        }
        /*
        else {


            $response = $this->render(
                'admin/ui/panel/footer/footer.html.twig',
            );
        }
        */
        else {
            $response = new Response();
        }
        return $response;
    }

}