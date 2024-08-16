<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\MasterData\Customer\CustomerController;
use App\Controller\Transaction\Order\Admin\Header\OrderHeaderController;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Service\Admin\SideBar\Action\PanelActionListMapBuilder;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class ContentController extends AbstractController
{

    public function dashboard(Request $request, RouterInterface $router,
        PanelActionListMapBuilder $builder,
    ): Response {
        return $this->render(
            'admin/customer/dashboard/dashboard.html.twig',
            ['request' => $request]
        );

    }

    /**
     * @param Request                $request
     * @param CustomerFromUserFinder $customerFromUserFinder
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function profile(Request $request, CustomerFromUserFinder $customerFromUserFinder,): Response
    {


        $customer = $customerFromUserFinder->getLoggedInCustomer();


        return $this->forward(CustomerController::class . '::edit', [
            'id' => $customer->getId(),
            'request' => $request]);

    }


    /**
     * @param Request $request
     * @return Response
     */
    public function orders(Request $request):
    Response
    {

        return $this->forward(OrderHeaderController::class . '::list', ['request' => $request]);

    }

    public function orderDisplay(Request $request): Response
    {

        $routeParams = $request->attributes->get('_route_params');
        $id = $routeParams['id'];

        return $this->forward(OrderHeaderController::class . '::display', ['request' => $request,'id'=>$id]);

    }

}