<?php
// src/Controller/InventoryProductController.php
namespace App\Controller\MasterData\Inventory;

// ...
use App\Form\MasterData\Inventory\DTO\InventoryProductDTO;
use App\Form\MasterData\Inventory\InventoryProductCreateForm;
use App\Form\MasterData\Inventory\InventoryProductEditForm;
use App\Repository\InventoryProductRepository;
use App\Repository\ProductRepository;
use App\Service\MasterData\Inventory\Mapper\InventoryProductDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventoryProductController extends AbstractController
{

    #[\Symfony\Component\Routing\Attribute\Route('/inventory/product/$id/create', 'inventory_product_create')]
    public function create(int $id, InventoryProductDTOMapper $inventoryProductDTOMapper,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager, Request $request
    ): Response {

        $product = $productRepository->find($id);

        $inventoryDTO = new InventoryProductDTO();

        $inventoryDTO->productId = $product->getId();

        $form = $this->createForm(
            InventoryProductCreateForm::class, $inventoryDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $inventoryProduct = $inventoryProductDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($inventoryProduct);
            $entityManager->flush();


            $id = $inventoryProduct->getId();

            $this->addFlash(
                'success', "Inventory created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Inventory created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render(
            'master_data/inventory/inventory_product_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/inventory/{id}/edit', name: 'inventory_product_edit')]
    public function edit(EntityManagerInterface $entityManager,
        InventoryProductRepository $inventoryProductRepository,
        InventoryProductDTOMapper $inventoryDTOMapper, Request $request,
        int $id
    ): Response {
        $inventory = $inventoryProductRepository->find($id);


        if (!$inventory) {
            throw $this->createNotFoundException('No inventory found for id ' . $id);
        }

        $inventoryDTO = new InventoryProductDTO();
        $inventoryDTO->id = $id;

        $form = $this->createForm(InventoryProductEditForm::class, $inventoryDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $inventory = $inventoryDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($inventory);
            $entityManager->flush();

            $id = $inventory->getId();

            $this->addFlash(
                'success', "Inventory updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Inventory updated successfully"]
                ), 200
            );
        }

        return $this->render(
            'master_data/inventory/inventory_product_edit.html.twig', ['form' => $form]
        );
    }

    #[Route('/inventory/{id}/display', name: 'inventory_product_display')]
    public function display(InventoryProductRepository $inventoryProductRepository, int $id
    ): Response {
        $inventory = $inventoryProductRepository->find($id);
        if (!$inventory) {
            throw $this->createNotFoundException('No inventory found for id ' . $id);
        }

        $inventoryObject = [
            'id' => $inventory->getId(),
            'name' => $inventory->getProduct()->getName(),
            'quantity' => $inventory->getQuantity()
        ];

        $displayParams = ['title' => 'Inventory Product',
                          'link_id' => 'id-inventory',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'id',
                                        'propertyName' => 'id',
                                        'link_id' => 'id-display-inventory'],
                                       ['label' => 'Product Name',
                                        'propertyName' => 'name'],
                                       ['label' => 'Quantity',
                                        'propertyName' => 'quantity'],]];

        return $this->render(
            'master_data/inventory/inventory_product_display.html.twig',
            ['entity' => $inventoryObject, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/inventory/list', name: 'inventory_product_list')]
    public function list(InventoryProductRepository $inventoryProductRepository,
        PaginatorInterface $paginator,
        Request $request
    ):
    Response {

        $listGrid = ['title' => 'Inventory',
                     'link_id' => 'id-inventory',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description', 'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => ' id-create-inventory',
                                              'function' => 'inventory',
                                              'anchorText' => 'Create Inventory']];

        $query = $inventoryProductRepository->getQueryForSelect();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            1 /*limit per page*/
        );

        return $this->render(
            'admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid]
        );
    }
}