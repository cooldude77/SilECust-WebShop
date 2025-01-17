<?php

namespace App\Controller\Module\WebShop\External\Shop;

use App\Controller\Module\WebShop\External\Product\ProductController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends EnhancedAbstractController
{

    public function content(Request $request): Response
    {

        if ($request->query->get('searchTerm')) {
            return $this->forward(ProductController::class . '::' . 'listBySearchTerm', ['request'
                                                                                         => $request]
            );
        }
        elseif ($request->query->get('filter')) {
            return $this->forward(ProductController::class . '::' . 'listByFilter', ['request'
                                                                                         => $request]
            );
        } else {
            return $this->forward(ProductController::class . '::' . 'list', ['request' => $request]
            );
        }
    }

}