<?php

namespace App\Controller\Modules\Webshop;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebShopController extends AbstractController
{
    #[Route('/shop', name: 'module_web_shop')]
    public function createProduct(EntityManagerInterface $entityManager, Request $request): Response
    {

        return $this->render('admin/ui/panel/panel.html.twig');
    }

}