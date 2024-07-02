<?php

namespace App\Controller\Module\WebShop\External\Shop;

use App\Form\Module\WebShop\External\Shop\HeaderSearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeaderController extends AbstractController
{

    public function header(Request $request): Response
    {

        $form = $this->createForm(HeaderSearchForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $searchTerm = $form->get('searchTerm')->getData();
            $redirectUrl = $this->generateUrl('home', ['searchTerm' => $searchTerm]);

            return new RedirectResponse($redirectUrl);
        }

        return $this->render(
            'module/web_shop/external/shop/header.html.twig',
            ['form' => $form]
        );


    }

}