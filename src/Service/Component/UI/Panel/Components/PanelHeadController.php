<?php

namespace Silecust\WebShop\Service\Component\UI\Panel\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\UI\Panel\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Service\Component\UI\Panel\Session\SessionAndMethodChecker;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanelHeadController extends EnhancedAbstractController
{
    public const string HEAD_CONTROLLER_CLASS_NAME = 'HEAD_CONTROLLER_CLASS_NAME';
    public const string HEAD_CONTROLLER_CLASS_METHOD_NAME = 'HEAD_CONTROLLER_CLASS_METHOD_NAME';

    public function head(Request                  $request,
                         SessionInterface         $session,
                         SessionAndMethodChecker  $sessionAndMethodChecker,
                         EventDispatcherInterface $eventDispatcher): Response
    {

        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request),
            PreHeadForwardingEvent::PRE_HEAD_FORWARDING_EVENT
        );

        if (
            $sessionAndMethodChecker->checkSessionVariablesAndMethod(
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
                'common/ui/panel/head/head.html.twig'
            );
        }
        */
        else {
            $response = new Response();
        }

        return $response;
    }


}