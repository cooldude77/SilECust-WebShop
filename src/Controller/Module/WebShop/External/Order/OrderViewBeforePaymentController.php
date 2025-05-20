<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Order;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Event\Transaction\Order\Header\BeforeOrderViewEvent;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Silecust\WebShop\Service\Transaction\Order\OrderRead;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderViewBeforePaymentController extends EnhancedAbstractController
{


    /**
     *
     * @param Request $request
     *
     * @return Response
     *
     *  Create Order after checkout and before payment.
     *  Payment info to be added later
     */
    #[Route('/checkout/order/view', name: 'sc_web_shop_view_order')]
    public function view(Request $request): Response
    {


        $session = $request->getSession();

        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_NAME, HeadController::class
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_METHOD_NAME, 'head'
        );

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'order'
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            '@SilecustWebShop/module/web_shop/external/order/page/order_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }


    /**
     * @param OrderRead $orderRead
     * @param CustomerFromUserFinder $customerFromUserFinder
     *
     * @return Response
     * @throws UserNotLoggedInException
     * @throws UserNotAssociatedWithACustomerException
     */
    public function order(OrderRead                $orderRead,
                          CustomerFromUserFinder   $customerFromUserFinder,
                          EventDispatcherInterface $eventDispatcher,
    ): Response
    {

        $orderHeader = $orderRead->getOpenOrder($customerFromUserFinder->getLoggedInCustomer());


        $eventDispatcher->dispatch(new BeforeOrderViewEvent($orderHeader), BeforeOrderViewEvent::BEFORE_ORDER_VIEW_EVENT);

        $orderObject = $orderRead->getOrderObject($orderHeader);

        //todo: order object validator

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/order/order_view.html.twig',
            ['orderObject' => $orderObject]
        );
    }

}