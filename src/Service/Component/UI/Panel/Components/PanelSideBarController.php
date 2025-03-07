<?php

namespace Silecust\WebShop\Service\Component\UI\Panel\Components;

use Silecust\WebShop\Service\Component\UI\Panel\Session\SessionAndMethodChecker;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelSideBarController extends EnhancedAbstractController
{
    public const string SIDE_BAR_CONTROLLER_CLASS_NAME = 'SIDE_BAR_CONTROLLER_CLASS_NAME';
    public const string SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME = 'SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME';


    public function sideBar(SessionInterface $session, Request $request,SessionAndMethodChecker $sessionAndMethodChecker): Response
    {
        if (
            $sessionAndMethodChecker->checkSessionVariablesAndMethod(
            self::SIDE_BAR_CONTROLLER_CLASS_NAME,
            self::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME)
        ) {

            $response = $this->forward(
                $session->get(self::SIDE_BAR_CONTROLLER_CLASS_NAME)
                . "::"
                . $session->get(self::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME),
                ['request' => $request]
            );
            // clear session variables after content has been retrieved
            $session->set(self::SIDE_BAR_CONTROLLER_CLASS_NAME, null);
            $session->set(self::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME, null);

        } else {
            $response = new Response();
        }

        return $response;
    }

}