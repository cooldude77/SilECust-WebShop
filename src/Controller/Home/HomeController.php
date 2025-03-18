<?php
// src/Controller/CustomerController.php
namespace Silecust\WebShop\Controller\Home;

// ...
use Silecust\WebShop\Controller\Module\WebShop\External\Shop\HomePageController;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends EnhancedAbstractController
{
    #[Route('/', name: 'home')]
    public function home( Request $request): Response
    {
        return $this->forward(HomePageController::class.'::'.'shop',['request'=>$request]);
    }

}