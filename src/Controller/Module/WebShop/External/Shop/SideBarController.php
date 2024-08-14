<?php

namespace App\Controller\Module\WebShop\External\Shop;

use App\Controller\Module\WebShop\External\Category\CategoryController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SideBarController extends AbstractController
{

    public function sideBar(Request $request): Response
    {
        return $this->forward(CategoryController::class . '::' . 'list', ['request' => $request]);
    }

}