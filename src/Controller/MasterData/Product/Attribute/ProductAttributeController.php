<?php
// src/Controller/ProductController.php
namespace Silecust\WebShop\Controller\MasterData\Product\Attribute;

// ...
use Knp\Component\Pager\PaginatorInterface;
use Silecust\WebShop\Form\MasterData\Product\Attribute\DTO\ProductAttributeDTO;
use Silecust\WebShop\Form\MasterData\Product\Attribute\ProductAttributeCreateForm;
use Silecust\WebShop\Form\MasterData\Product\Attribute\ProductAttributeEditForm;
use Silecust\WebShop\Repository\ProductAttributeRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Product\Attribute\ProductAttributeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductAttributeController extends EnhancedAbstractController
{
    #[Route('/admin/product/attribute/create', name: 'sc_admin_product_attribute_create')]
    public function create(ProductAttributeDTOMapper $mapper, EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $productAttributeDTO = new ProductAttributeDTO();

        $form = $this->createForm(ProductAttributeCreateForm::class, $productAttributeDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttribute = $mapper->mapDtoToEntity($form->getData());

            $entityManager->persist($productAttribute);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product created successfully"
            );

            $id = $productAttribute->getId();
            $this->addFlash(
                'success', "Product Attribute created successfully"
            );

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


    #[\Symfony\Component\Routing\Attribute\Route('/admin/product/attribute/{id}/edit', name: 'sc_admin_product_attribute_edit')]
    public function edit(int $id, ProductAttributeDTOMapper $mapper,
        EntityManagerInterface $entityManager,
        ProductAttributeRepository $productAttributeRepository, Request $request
    ): Response {
        $productAttributeDTO = new ProductAttributeDTO();

        $productAttributeEntity = $productAttributeRepository->find($id);

        $form = $this->createForm(ProductAttributeEditForm::class, $productAttributeDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $productAttributeEntity = $mapper->mapDtoToEntityForEdit(
                $form->getData(), $productAttributeEntity
            );

            $entityManager->persist($productAttributeEntity);
            $entityManager->flush();

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


    #[Route('/admin/product/attribute/list', name: 'sc_admin_product_attribute_list')]
    public function list(ProductAttributeRepository $productAttributeRepository,
                         PaginatorInterface    $paginator,
                         SearchEntityInterface $searchEntity,
                         Request               $request): Response
    {
        $this->setContentHeading($request, 'Product Attributes');

        $listGrid = ['title' => 'Product Attribute',
                     'link_id' => 'id-product-attribute',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display'],
                                   ['label' => 'Description',
                                    'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => 'id-create-product_attribute',
                                              'function' => 'product_attribute',
                                              'anchorText' => 'Create Product Attribute']];

        $query = $searchEntity->getQueryForSelect($request, $productAttributeRepository,
            ['name', 'description']);

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