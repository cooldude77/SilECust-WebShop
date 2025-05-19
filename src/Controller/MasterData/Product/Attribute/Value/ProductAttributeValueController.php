<?php

namespace Silecust\WebShop\Controller\MasterData\Product\Attribute\Value;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeValueDTO;
use Silecust\WebShop\Form\MasterData\Product\Attribute\Value\ProductAttributeValueCreateForm;
use Silecust\WebShop\Form\MasterData\Product\Attribute\Value\ProductAttributeValueEditForm;
use Silecust\WebShop\Repository\ProductAttributeValueRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Product\Attribute\Value\ProductAttributeValueDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductAttributeValueController extends EnhancedAbstractController
{

    #[Route('/admin/product/attribute/{id}/value/create', name: 'sc_admin_product_attribute_value_create')]
    public function create(int                    $id, ProductAttributeValueDTOMapper $mapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $productAttributeValueDTO = new ProductAttributeValueDTO();
        $productAttributeValueDTO->productAttributeId = $id;

        $form = $this->createForm(
            ProductAttributeValueCreateForm::class, $productAttributeValueDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttributeValue = $mapper->mapDtoToEntityForCreate($form->getData());

            $entityManager->persist($productAttributeValue);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product Attribute created successfully"
            );

            $id = $productAttributeValue->getId();

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Attribute created successfully"]
                ), 200
            );

        }

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[\Symfony\Component\Routing\Attribute\Route('/admin/product/attribute/value/{id}/edit', name: 'sc_admin_product_attribute_value_edit')]
    public function edit(int                             $id, ProductAttributeValueDTOMapper $mapper,
                         EntityManagerInterface          $entityManager,
                         ProductAttributeValueRepository $productAttributeValueRepository, Request $request
    ): Response
    {
        $productAttributeValueDTO = new ProductAttributeValueDTO();

        $productAttributeValueEntity = $productAttributeValueRepository->find($id);

        $form = $this->createForm(ProductAttributeValueEditForm::class, $productAttributeValueDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttributeEntity = $mapper->mapDtoToEntityForUpdate(
                $form->getData(), $productAttributeValueEntity
            );

            $entityManager->persist($productAttributeEntity);
            $entityManager->flush();


            $id = $productAttributeEntity->getId();
            $this->addFlash(
                'success', "Product Attribute Value updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Attribute Value updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/common/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }


    #[Route("/product/attribute/{id}/value/list", name: 'sc_admin_product_attribute_value_list')]
    public function list(ProductAttributeValueRepository $productAttributeValueRepository,
                         PaginatorInterface              $paginator,
                         SearchEntityInterface           $searchEntity,
                         Request                         $request
    ): Response
    {
        $this->setContentHeading($request, 'Product Attribute Values');

        $listGrid = ['title' => 'Product Attribute Values',
            'link_id' => 'id-product-attribute-value',
            'columns' => [['label' => 'Name',
                'propertyName' => 'name',
                'action' => 'display'],
                ['label' => 'value',
                    'propertyName' => 'value'],],
            'createButtonConfig' => ['link_id' => 'id-create-product-attribute-value',
                'function' => 'product_attribute_value',
                'anchorText' => 'Create Product Attribute Value']];

        $query = $searchEntity->getQueryForSelect($request, $productAttributeValueRepository, ['name'],
            Criteria::create(Criteria::expr()->eq('productAttribute', $request->get('id'))));


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }

}