<?php

namespace Silecust\WebShop\Controller\Module\WebShop\Admin;

use Silecust\WebShop\Form\Module\WebShop\Admin\DTO\WebShopDTO;
use Silecust\WebShop\Form\Module\WebShop\Admin\WebShopCreateForm;
use Silecust\WebShop\Form\Module\WebShop\Admin\WebShopEditForm;
use Silecust\WebShop\Repository\WebShopRepository;
use Silecust\WebShop\Service\Module\WebShop\Admin\Mapper\WebShopDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebShopAdminController extends EnhancedAbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/web-shop/create', 'web_shop_create')]
    public function create(WebShopDTOMapper $webShopDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $webShopDTO = new WebShopDTO();
        $form = $this->createForm(
            WebShopCreateForm::class, $webShopDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $webShopEntity = $webShopDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($webShopEntity);
            $entityManager->flush();


            $id = $webShopEntity->getId();

            $this->addFlash(
                'success', "Web Shop created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "WebShop created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('@SilecustWebShop/module/web_shop/admin/web_shop/web_shop_create.html.twig', ['form' => $form]);
    }


    #[Route('/web-shop/{id}/edit', name: 'web_shopedit')]
    public function edit(EntityManagerInterface $entityManager,
        WebShopRepository $webShopRepository, WebShopDTOMapper $webShopDTOMapper, Request $request,
        int $id
    ): Response {
        $webShop = $webShopRepository->find($id);


        if (!$webShop) {
            throw $this->createNotFoundException('No webShop found for id ' . $id);
        }

        $webShopDTO = new WebShopDTO();
        $webShopDTO->id = $id;

        $form = $this->createForm(WebShopEditForm::class, $webShopDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $webShop = $webShopDTOMapper->mapToEntityForEdit($webShopDTO, $webShop);
            // perform some action...
            $entityManager->persist($webShop);
            $entityManager->flush();

            $id = $webShop->getId();

            $this->addFlash(
                'success', "Web Shop updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Web Shop updated successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/module/web_shop/admin/web_shop/web_shop_edit.html.twig', ['form' => $form]);
    }

    #[Route('/web-shop/{id}/display', name: 'web_shop_display')]
    public function display(WebShopRepository $webShopRepository, int $id): Response
    {
        $webShop = $webShopRepository->find($id);
        if (!$webShop) {
            throw $this->createNotFoundException('No webShop found for id ' . $id);
        }

        $displayParams = ['title' => 'WebShop',
                          'link_id' => 'id-webShop',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Name',
                                        'propertyName' => 'name',
                                        'link_id' => 'id-display-webShop'],
                                       ['label' => 'Description',
                                        'propertyName' => 'description'],]];

        return $this->render(
            '@SilecustWebShop/module/web_shop/admin/web_shop/web_shop_display.html.twig',
            ['entity' => $webShop, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/web-shop/list', name: 'web_shop_list')]
    public function list(WebShopRepository $webShopRepository, PaginatorInterface $paginator,
        Request $request
    ): Response {

        $listGrid = ['title' => 'WebShop',
                     'link_id' => 'id-webShop',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description', 'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => ' id-create-webShop',
                                              'function' => 'webShop',
                                              'anchorText' => 'Create WebShop']];

        $query = $webShopRepository->getQueryForSelect();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */ $request->query->getInt('page', 1),
            /*page number*/ 1 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid]
        );
    }
}