<?php

namespace App\Controller\Module\WebShop\External\Shop;

use App\Controller\Module\WebShop\External\Product\ProductController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends AbstractController
{

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