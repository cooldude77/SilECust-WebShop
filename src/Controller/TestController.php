<?php

namespace Silecust\WebShop\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController
{
    #[Route(path: '/test', name: 'test')]
    public function home(): Response
    {
        return new Response("Hello");

    }
}