<?php

namespace Silecust\WebShop\Controller\Module\WebShop\Admin;


use Silecust\WebShop\Form\Module\WebShop\Admin\Section\DTO\WebShopSectionDTO;
use Silecust\WebShop\Form\Module\WebShop\Admin\Section\Mapper\WebShopSectionDTOMapper;
use Silecust\WebShop\Form\Module\WebShop\Admin\Section\WebShopSectionCreateForm;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebShopAdminSectionController extends EnhancedAbstractController
{
    #[Route('/web-shop/section/create', name: 'sc_module_web_shop_section_create')]
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

            $response = $this->render('@SilecustWebShop/module/web_shop/web_shop/section/admin/success.html.twig');
            $response->setStatusCode(401);

            return $response;
        }
        return $this->render('@SilecustWebShop/module/web_shop/admin/web_shop/section/create.html.twig', ['form' => $form]);


    }
}