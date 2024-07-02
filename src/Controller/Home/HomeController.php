<?php
// src/Controller/CustomerController.php
namespace App\Controller\Home;

// ...
use App\Controller\Module\WebShop\External\Shop\MainController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home( Request $request): Response
    {
        return $this->forward(MainController::class.'::'.'shop',['request'=>$request]);
    }

}