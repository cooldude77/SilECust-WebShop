<?php

namespace App\Controller\Module\WebShop\External\Order;

use App\Repository\OrderHeaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderConfirmationController extends AbstractController
{


    #[Route('/order/{id}/success', 'module_web_shop_order_complete_details')]
    public function thankYou(int $id, OrderHeaderRepository $orderHeaderRepository): Response
    {
        // todo: check referring route
        // this page will be displayed only when referred from payment

        $orderHeader = $orderHeaderRepository->find($id);

        return $this->render(
            'module/web_shop/external/order/thank_you_for_your_order.html.twig',
            ['orderHeader' => $orderHeader]
        );

    }
}