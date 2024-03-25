<?php

namespace App\Controller\Module\WebShop\Admin;


use App\Form\Module\WebShop\Admin\Section\DTO\WebShopSectionDTO;
use App\Form\Module\WebShop\Admin\Section\Mapper\WebShopSectionDTOMapper;
use App\Form\Module\WebShop\Admin\Section\WebShopSectionCreateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebShopAdminSectionController extends AbstractController
{
    #[Route('/web-shop/section/create', name: 'module_web_shop_section_create')]
    public function createWebShopSection(EntityManagerInterface $entityManager,
                                         WebShopSectionDTOMapper $webShopSectionDTOMapper,
                                  Request $request)
    {

        $webShopSectionDTO = new WebShopSectionDTO();

        $form = $this->createForm(WebShopSectionCreateForm::class, $webShopSectionDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $webShopSectionEntity = $webShopSectionDTOMapper->map($form->getData());

            // perform some action...
            $entityManager->persist($webShopSectionEntity);
            $entityManager->flush();

            $response = $this->render('module/web_shop/web_shop/section/admin/success.html.twig');
            $response->setStatusCode(401);

            return $response;
        }
        return $this->render('module/web_shop/admin/web_shop/section/create.html.twig', ['form' => $form]);


    }
}