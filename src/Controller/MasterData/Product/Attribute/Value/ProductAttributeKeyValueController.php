<?php

namespace App\Controller\MasterData\Product\Attribute\Value;

use App\Entity\ProductAttributeKey;
use App\Form\MasterData\Product\Attribute\Value\DTO\ProductAttributeKeyValueDTO;
use App\Form\MasterData\Product\Attribute\Value\ProductAttributeKeyValueCreateForm;
use App\Form\MasterData\Product\Attribute\Value\ProductAttributeKeyValueEditForm;
use App\Repository\ProductAttributeKeyValueRepository;
use App\Service\MasterData\Product\Attribute\Value\ProductAttributeKeyValueDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductAttributeKeyValueController extends AbstractController
{

    #[Route('/admin/product/attribute/{id}/value/create', name: 'sc_route_admin_product_attribute_value_create')]
    public function create(int                    $id, ProductAttributeKeyValueDTOMapper $mapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response {
        $ProductAttributeKeyValueDTO = new ProductAttributeKeyValueDTO();
        $ProductAttributeKeyValueDTO->ProductAttributeKeyId = $id;

        $form = $this->createForm(
            ProductAttributeKeyValueCreateForm::class, $ProductAttributeKeyValueDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ProductAttributeKeyValue = $mapper->mapDtoToEntityForCreate($form->getData());

            $entityManager->persist($ProductAttributeKeyValue);
            $entityManager->flush();

            $this->addFlash(
                'success', "Product Attribute created successfully"
            );

            $id = $ProductAttributeKeyValue->getId();

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Product Attribute created successfully"]
                ), 200
            );

        }

        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[\Symfony\Component\Routing\Attribute\Route('/admin/product/attribute/value/{id}/edit', name: 'sc_route_admin_product_attribute_value_edit')]
    public function edit(int                                $id, ProductAttributeKeyValueDTOMapper $mapper,
                         EntityManagerInterface             $entityManager,
                         ProductAttributeKeyValueRepository $ProductAttributeKeyValueRepository, Request $request
    ): Response {
        $ProductAttributeKeyValueDTO = new ProductAttributeKeyValueDTO();

        $ProductAttributeKeyValueEntity = $ProductAttributeKeyValueRepository->find($id);

        $form = $this->createForm(ProductAttributeKeyValueEditForm::class, $ProductAttributeKeyValueDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ProductAttributeKeyEntity = $mapper->mapDtoToEntityForUpdate(
                $form->getData(), $ProductAttributeKeyValueEntity
            );

            $entityManager->persist($ProductAttributeKeyEntity);
            $entityManager->flush();


            $id = $ProductAttributeKeyEntity->getId();
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
            'admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }


    #[Route("/product/attribute/{id}/value/list", name: 'sc_route_admin_product_attribute_value_list')]
    public function list(ProductAttributeKey $productAttributeKey, ProductAttributeKeyValueRepository $productAttributeKeyValueRepository, Request $request
    ): Response {

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

        $productAttributeKeyValues = $productAttributeKeyValueRepository->findBy(
            ['productAttributeKey' => $productAttributeKey]
        );
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request,'entities' => $productAttributeKeyValues, 'listGrid' => $listGrid]
        );
    }

}