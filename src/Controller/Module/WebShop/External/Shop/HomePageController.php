<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Shop;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\FooterController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\SideBarController;
use Silecust\WebShop\Controller\Module\WebShop\External\Product\ProductController;
use Silecust\WebShop\Controller\Module\WebShop\External\Shop\Components\ContentController;
use Silecust\WebShop\Event\Module\WebShop\External\Framework\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelFooterController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelSideBarController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomePageController extends EnhancedAbstractController
{

    /**
     * @param Request $request
     * @param SessionInterface $session
     *
     * @return Response
     *
     * Home redirects to here
     */
    public function shop(Request $request, SessionInterface $session,EventDispatcherInterface $eventDispatcher): Response
    {


        $session->set(PanelMainController::CONTEXT_ROUTE_SESSION_KEY, 'sc_home');

        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_NAME, SideBarController::class
        );
        $session->set(
            PanelSideBarController::SIDE_BAR_CONTROLLER_CLASS_METHOD_NAME,
            'sideBar'
        );

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );

        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request),
            PreHeadForwardingEvent::EVENT_NAME
        );

        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_NAME, HeadController::class
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_METHOD_NAME,
            'head'
        );

        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, static::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'content'
        );

        $session->set(
            PanelFooterController::FOOTER_CONTROLLER_CLASS_NAME, FooterController::class
        );
        $session->set(
            PanelFooterController::FOOTER_CONTROLLER_CLASS_METHOD_NAME,
            'footer'
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            '@SilecustWebShop/module/web_shop/external/shop/page/web_shop_home_page.html.twig'
        );

        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    public function content(Request $request): Response
    {

        if ($request->query->get('searchTerm')) {
            return $this->forward(ProductController::class . '::' . 'listBySearchTerm', ['request'
                => $request]
            );
        } else {
            return $this->forward(ProductController::class . '::' . 'list', ['request' => $request]
            );
        }
    }
}