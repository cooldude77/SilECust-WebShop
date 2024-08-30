<?php

namespace App\Controller;

use Own\self\MyOwn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestCustomizationController extends AbstractController
{
    #[Route('/test/route', name: 'test_custom_route_controller')]
    public function test()
    {

        $own = new MyOwn();
        return $this->render('my_file.html.twig');
    }

}