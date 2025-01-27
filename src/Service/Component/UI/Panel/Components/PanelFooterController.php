<?php

namespace App\Service\Component\UI\Panel\Components;

use App\Service\Component\UI\Panel\Session\SessionAndMethodChecker;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelFooterController extends EnhancedAbstractController
{
    public const string FOOTER_CONTROLLER_CLASS_NAME = 'FOOTER_CONTROLLER_CLASS_NAME';
    public const string FOOTER_CONTROLLER_CLASS_METHOD_NAME = 'FOOTER_CONTROLLER_CLASS_METHOD_NAME';


    public function footer(Request $request, SessionInterface $session,SessionAndMethodChecker $sessionAndMethodChecker): Response
    {
        if (
            $sessionAndMethodChecker->checkSessionVariablesAndMethod(
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
                'common/ui/panel/footer/footer.html.twig',
            );
        }
        */
        else {
            $response = new Response();
        }
        return $response;
    }

}