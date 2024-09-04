<?php
// src/Controller/ProductGroupController.php
namespace App\Controller\MasterData\Product\Group;

// ...
use App\Form\MasterData\Product\DTO\ProductDTO;
use App\Form\MasterData\Product\ProductCreateForm;
use App\Form\MasterData\Product\ProductEditForm;
use App\Repository\ProductRepository;
use App\Service\Component\UI\Search\SearchEntityInterface;
use App\Service\MasterData\Product\Mapper\ProductDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductGroupController extends AbstractController
{


    #[Route('/admin/product-group/create', 'sc_admin_product_group_create')]
    public function create(ProductDTOMapper       $productDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           ValidatorInterface     $validator
    ): Response
    {
        $productDTO = new ProductDTO();
        $form = $this->createForm(
            ProductCreateForm::class, $productDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $productEntity = $productDTOMapper->mapToEntityForCreate($data);

            $errors = $validator->validate($productEntity);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($productEntity);
                $entityManager->flush();


                $id = $productEntity->getId();

                $this->addFlash(
                    'success', "Product created successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Product created successfully"]
                    ), 200
                );
            }
        }
        return $this->render('master_data/productGroup/productGroup_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/product-group/{id}/edit', name: 'sc_admin_product_group_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         ProductRepository      $productRepository, ProductDTOMapper $productDTOMapper, Request $request,
                         int                    $id,

                         ValidatorInterface     $validator
    ): Response
    {
        $product = $productRepository->find($id);


        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $productDTO = $productDTOMapper->mapToDtoFromEntityForEdit($product);

        $form = $this->createForm(ProductEditForm::class, $productDTO, ['validation_groups' => ['edit']]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $product = $productDTOMapper->mapToEntityForEdit($data);


            $errors = $validator->validate($product);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($product);
                $entityManager->flush();

                $id = $product->getId();

                $this->addFlash(
                    'success', "Product updated successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $id, 'message' => "Product updated successfully"]
                    ), 200
                );
            }
        }

        return $this->render('master_data/productGroup/productGroup_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/product-group/{id}/display', name: 'sc_admin_product_group_display')]
    public function display(ProductRepository $productRepository, int $id, Request $request): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Product',
            'link_id' => 'id-product',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'Name',
                'propertyName' => 'name',
                'link_id' => 'id-display-product'],
                ['label' => 'Description',
                    'propertyName' => 'description'],]];

        return $this->render(
            'master_data/productGroup/productGroup_display.html.twig',
            ['request' => $request, 'entity' => $product, 'params' => $displayParams]
        );

    }

    #[Route('/admin/product-group/list', name: 'product_group_list')]
    public function list(ProductRepository     $productRepository,
                         PaginatorInterface    $paginator,
                         #[Autowire(service: 'product.search')]
                         SearchEntityInterface $searchEntity,
                         Request               $request):
    Response
    {

        $listGrid = [
            'title' => 'Product',
            'link_id' => 'id-product',
            'function' => 'product',
            'columns' => [
                ['label' => 'Name',
                    'propertyName' => 'name',
                    'action' => 'display',],
                ['label' => 'Description', 'propertyName' => 'description'],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-product',
                'anchorText' => 'Create Product']
        ];
        if ($request->query->get('searchTerm') != null)
            $searchCriteria = $searchEntity->searchByTerm($request->query->get('searchTerm'));

        $query = $productRepository->getQueryForSelect(isset($searchCriteria) ? $searchCriteria : null);

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