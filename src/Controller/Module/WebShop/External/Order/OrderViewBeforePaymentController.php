<?php

namespace App\Controller\Module\WebShop\External\Order;

use App\Controller\Module\WebShop\External\Shop\Components\HeaderController;
use App\Event\Transaction\Order\Header\BeforeOrderViewEvent;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Component\UI\Panel\Components\PanelContentController;
use App\Service\Component\UI\Panel\Components\PanelHeaderController;
use App\Service\Component\UI\Panel\PanelMainController;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use App\Service\Transaction\Order\OrderRead;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
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
    #[Route('/checkout/order/view', 'web_shop_view_order')]
    public function view(Request $request): Response
    {


        $session = $request->getSession();

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
            'module/web_shop/external/order/page/order_page.html.twig'
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
            'module/web_shop/external/order/order_view.html.twig',
            ['orderObject' => $orderObject]
        );
    }

}