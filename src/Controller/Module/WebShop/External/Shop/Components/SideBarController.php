<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Shop\Components;

use Silecust\WebShop\Controller\Module\WebShop\External\Category\CategoryController;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SideBarController extends EnhancedAbstractController
{

    public function sideBar(Request $request): Response
    {
        return $this->forward(CategoryController::class . '::' . 'list', ['request' => $request]);
    }

}