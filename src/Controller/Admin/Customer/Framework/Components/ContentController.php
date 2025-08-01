<?php /** @noinspection ALL */

namespace Silecust\WebShop\Controller\Admin\Customer\Framework\Components;

use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\MasterData\Customer\Address\CustomerAddressController;
use Silecust\WebShop\Controller\MasterData\Customer\CustomerController;
use Silecust\WebShop\Controller\Transaction\Order\Admin\Header\OrderHeaderController;
use Silecust\WebShop\Controller\Transaction\Order\Admin\Item\OrderItemController;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Service\Admin\SideBar\Action\PanelActionListMapBuilder;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class ContentController extends EnhancedAbstractController
{

    public function dashboard(Request                   $request, RouterInterface $router,
                              PanelActionListMapBuilder $builder,
    ): Response
    {
        return $this->render(
            '@SilecustWebShop/admin/customer/dashboard/dashboard.html.twig',
            ['request' => $request]
        );

    }

    /**
     * @param Request $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function profile(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {


        $customer = $customerFromUserFinder->getLoggedInCustomer();


        return
            $this->render(
                '@SilecustWebShop/admin/customer/dashboard/dashboard.html.twig',
                ['request' => $request,
                    'id' => $customer->getId()
                ]);

    }


    /**
     * @param Request $request
     * @return Response
     */
    public function orders(Request $request):
    Response
    {
        $this->setContentHeading($request, "Your Orders");
        return $this->forward(OrderHeaderController::class . '::list', ['request' => $request]);

    }

    /**
     * @param Request $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function addresses(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {

        $customer = $customerFromUserFinder->getLoggedInCustomer();

        return $this->forward(CustomerAddressController::class . '::list',
            ['request' => $request, 'id' => $customer->getId()]);

    }

    /**
     * @param Request $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function addressCreate(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {

        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $this->setContentHeading($request, 'Create new address');

        $formResponse = $this->forward(CustomerAddressController::class . '::create',
            ['request' => $request, 'id' => $customer->getId()]);


        if ($formResponse instanceof JsonResponse)
            return $this->redirect($this->generateUrl('sc_my_addresses'));

        return $this->render(
            '@SilecustWebShop/admin/customer/ui/my_generic_content.html.twig',
            [
                'content' => $formResponse->getContent()
            ]);

    }

    /**
     * @param Request $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function addressDisplay(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {

        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $formResponse = $this->forward(CustomerAddressController::class . '::display',
            ['request' => $request, 'id' => $request->attributes->get('id')]);


        if ($formResponse instanceof JsonResponse)
            return $this->redirect($this->generateUrl('sc_my_addresses'));

        return $this->render(
            '@SilecustWebShop/admin/customer/ui/my_generic_content.html.twig',
            [
                'content' => $formResponse->getContent()
            ]);

    }

    /**
     * @param Request $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function addressEdit(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {

        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $formResponse = $this->forward(CustomerAddressController::class . '::edit',
            ['request' => $request, 'id' => $request->attributes->get('id')]);


        if ($formResponse instanceof JsonResponse)
            return $this->redirect($this->generateUrl('sc_my_addresses'));

        return $this->render(
            '@SilecustWebShop/admin/customer/ui/my_generic_content.html.twig',
            [
                'content' => $formResponse->getContent()
            ]);

    }

    public function orderDisplay(Request $request): Response
    {

        $routeParams = $request->attributes->get('_route_params');

        return $this->forward(OrderHeaderController::class . '::display', ['request' => $request,
            'generatedId' => $request->attributes->get('generatedId')]);

    }

    public function orderItemDisplay(Request $request): Response
    {

        $routeParams = $request->attributes->get('_route_params');


        return $this->forward(OrderItemController::class . '::display', ['request' => $request, 'id' => $request->attributes->get('id')]);

    }

    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function personalInfo(Request $request, CustomerFromUserFinder $customerFromUserFinder): Response
    {

        $this->setContentHeading($request, 'Edit your personal information');

        $customer = $customerFromUserFinder->getLoggedInCustomer();


        $formResponse = $this->forward(CustomerController::class . '::edit', [
            'request' => $request, 'id' => $customer->getId()]);

        if ($formResponse instanceof JsonResponse)
            return $this->redirect($this->generateUrl('sc_my_personal_info'));

        return $this->render(
            '@SilecustWebShop/admin/customer/ui/my_generic_content.html.twig',
            [
                'content' => $formResponse->getContent()
            ]);

    }

}