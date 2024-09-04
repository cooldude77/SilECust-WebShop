<?php
// src/Controller/ProductGroupController.php
namespace App\Controller\MasterData\Product\Group;

// ...
use App\Entity\ProductGroup;
use App\Form\MasterData\Product\Group\DTO\ProductGroupDTO;
use App\Form\MasterData\Product\Group\ProductGroupCreateForm;
use App\Form\MasterData\Product\Group\ProductGroupEditForm;
use App\Form\MasterData\Product\ProductCreateForm;
use App\Form\MasterData\Product\ProductEditForm;
use App\Repository\ProductGroupRepository;
use App\Service\MasterData\Product\Group\ProductGroupDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductGroupController extends AbstractController
{


    #[Route('/admin/product-group/create', 'sc_route_admin_product_group_create')]
    public function create(ProductGroupDTOMapper       $productGroupDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           ValidatorInterface     $validator
    ): Response
    {
        $productGroupDTO = new ProductGroupDTO();
        $form = $this->createForm(
            ProductGroupCreateForm::class, $productGroupDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $productEntity = $productGroupDTOMapper->mapDtoToEntityForCreate($data);

            $errors = $validator->validate($productEntity);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($productEntity);
                $entityManager->flush();


                $id = $productEntity->getId();

                $this->addFlash(
                    'success', "Product Group created successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Product Group created successfully"]
                    ), 200
                );
            }
        }
        return $this->render('admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/productGroup-group/{id}/edit', name: 'sc_route_admin_product_group_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         ProductGroupRepository      $productGroupRepository, ProductGroupDTOMapper $productGroupDTOMapper, Request $request,
                        ProductGroup $productGroup,

                         ValidatorInterface     $validator
    ): Response
    {

        $productGroupDTO = $productGroupDTOMapper->mapToDtoFromEntityForEdit($productGroup);

        $form = $this->createForm(ProductGroupEditForm::class, $productGroupDTO, ['validation_groups' => ['edit']]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $productGroup = $productGroupDTOMapper->mapDtoToEntityForUpdate($data);

            $errors = $validator->validate($productGroup);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($productGroup);
                $entityManager->flush();

                $id = $productGroup->getId();

                $this->addFlash(
                    'success', "Product Group updated successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Product Group updated successfully"]
                    ), 200
                );
            }
        }

        return $this->render('master_data/product/group/product_group_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/product-group/{id}/display', name: 'sc_route_admin_product_group_display')]
    public function display(ProductGroupRepository $productGroupRepository, int $id, Request $request): Response
    {
        $product = $productGroupRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Product Group',
            'link_id' => 'id-product-group',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'Name',
                'propertyName' => 'name',
                'link_id' => 'id-display-product-group'],
                ['label' => 'Description',
                    'propertyName' => 'description'],]];

        return $this->render(
            'admin/ui/panel/section/content/display/display.html.twig',
            ['request' => $request, 'entity' => $product, 'params' => $displayParams]
        );

    }

    #[Route('/admin/product-group/list', name: 'sc_route_admin_product_group_list')]
    public function list(ProductGroupRepository  $productGroupRepository,
                         PaginatorInterface $paginator,
                         Request            $request):
    Response
    {

        $listGrid = [
            'title' => 'Product Group',
            'link_id' => 'id-product-group',
            'function' => 'product_group',
            'columns' => [
                ['label' => 'Name',
                    'propertyName' => 'name',
                    'action' => 'display',],
                ['label' => 'Description', 'propertyName' => 'description'],
            ],
            'createButtonConfig' => [
                'link_id' => ' id-create-product-group',
                'anchorText' => 'Create Product Group']
        ];
       
        $query = $productGroupRepository->getQueryForSelect();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render(
            'admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }
}