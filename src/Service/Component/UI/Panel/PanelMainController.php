<?php

namespace App\Service\Component\UI\Panel;

use App\Exception\Component\UI\BaseTemplateNotFoundPanelMainException;
use App\Service\Component\UI\Panel\Components\PanelContentController;
use App\Service\Component\UI\Panel\Components\PanelFooterController;
use App\Service\Component\UI\Panel\Components\PanelHeadController;
use App\Service\Component\UI\Panel\Components\PanelHeaderController;
use App\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

/**
 *  The panels logic will flow through this controller
 *  The main panel will display header, content, sidebar, footer
 *  The content panel will contain the actual route called
 */
class PanelMainController extends EnhancedAbstractController
{

    public const string CONTEXT_ROUTE_SESSION_KEY = 'context_route';
    public const string BASE_TEMPLATE = 'BASE_TEMPLATE';

    /**
     * @param Request $request
     * @param Environment $environment
     * @return Response
     *
     * The twig template has panels for header,content, sidebar and footer
     * @throws BaseTemplateNotFoundPanelMainException
     */
    public function main(Request $request, Environment $environment): Response
    {


        $this->checkMandatoryParameters($request->getSession(), $environment);

        // get head controller
        $headResponse = $this->forward(
            PanelHeadController::class . '::' . 'head', ['request' => $request]
        );

        // if redirect
        if ($headResponse instanceof RedirectResponse) {
            $this->resetParameters($request->getSession());
            return $this->redirect($headResponse->getTargetUrl());
        }

        // get header
        $headerResponse = $this->forward(
            PanelHeaderController::class . '::' . 'header', ['request' => $request]
        );

        // if redirect
        if ($headerResponse instanceof RedirectResponse) {
            $this->resetParameters($request->getSession());
            return $this->redirect($headerResponse->getTargetUrl());
        }

        // get content
        $contentResponse = $this->forward(
            PanelContentController::class . '::' . 'content', ['request' => $request]
        );

        // if redirect
        if ($contentResponse instanceof RedirectResponse) {
            $this->resetParameters($request->getSession());
            return $this->redirect($contentResponse->getTargetUrl());
        }

        // get sideBar
        $sideBarResponse = $this->forward(
            PanelSideBarController::class . '::' . 'sideBar', ['request' => $request]
        );

        // if redirect
        if ($sideBarResponse instanceof RedirectResponse) {
            $this->resetParameters($request->getSession());
            return $this->redirect($sideBarResponse->getTargetUrl());
        }

        // get footer
        $footerResponse = $this->forward(
            PanelFooterController::class . '::' . 'footer', ['request' => $request]
        );

        // if redirect
        if ($footerResponse instanceof RedirectResponse) {
            $this->resetParameters($request->getSession());
            return $this->redirect($footerResponse->getTargetUrl());
        }


        // no redirect, just print data
        $response = $this->render(
            'common/ui/panel/panel_main.html.twig', [
                'headResponse' => $headResponse->getContent(),
                'headerResponse' => $headerResponse->getContent(),
                'contentResponse' => $contentResponse->getContent(),
                'sideBarResponse' => $sideBarResponse->getContent(),
                'footerResponse' => $footerResponse->getContent(),
                'request' => $request]
        );


        // reset parameter is only to be done after above response is complete
        // otherwise it will throw up exception looking for parameters
        $this->resetParameters($request->getSession());

        return $response;
    }

    /**
     * @param SessionInterface $session
     * @param Environment $environment
     *
     * @return void
     * @throws BaseTemplateNotFoundPanelMainException
     */
    private function checkMandatoryParameters(SessionInterface $session, Environment $environment
    ): void
    {
        if ($session->get(self::BASE_TEMPLATE) != null) {
            if ($environment->getLoader()->exists($session->get(self::BASE_TEMPLATE))) {
                return;
            }
        }
        throw  new BaseTemplateNotFoundPanelMainException();

    }

    /**
     * @param SessionInterface $session
     *
     * @return void
     */
    private function resetParameters(SessionInterface $session): void
    {
        $session->set(self::BASE_TEMPLATE, null);
    }

}