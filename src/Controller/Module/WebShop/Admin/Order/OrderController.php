<?php

namespace App\Controller\Module\WebShop\Admin\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{


    #[Route(path: '/orders', name: 'order_list')]
    public function list(): Response
    {

        return new Response("List");

    }
}