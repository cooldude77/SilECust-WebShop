<?php

namespace App\Controller\Admin\Customer\Framework;

use App\Controller\MasterData\Customer\CustomerController;
use App\Service\Admin\Action\PanelActionListMapBuilder;
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

    public function profile(Request $request, CustomerFromUserFinder $customerFromUserFinder,): Response
    {


        $customer = $customerFromUserFinder->getLoggedInCustomer();


        return $this->forward(CustomerController::class . '::edit', [
            'id' => $customer->getId(),
            'request' => $request]);

    }

}