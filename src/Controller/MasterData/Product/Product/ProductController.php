<?php
// src/Controller/ProductController.php
namespace Silecust\WebShop\Controller\MasterData\Product\Product;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Product\DTO\ProductDTO;
use Silecust\WebShop\Form\MasterData\Product\ProductCreateForm;
use Silecust\WebShop\Form\MasterData\Product\ProductEditForm;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Product\Mapper\ProductDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Todo : Write tests for lists
 */
class ProductController extends EnhancedAbstractController
{


    #[Route('/admin/product/create', 'sc_admin_product_create')]
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
        return $this->render('@SilecustWebShop/master_data/product/product_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/product/{id}/edit', name: 'sc_admin_product_edit')]
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

        return $this->render('@SilecustWebShop/master_data/product/product_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/product/{id}/display', name: 'sc_admin_product_display')]
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
            '@SilecustWebShop/master_data/product/product_display.html.twig',
            ['request' => $request, 'entity' => $product, 'params' => $displayParams]
        );

    }

    /**
     * @throws QueryException
     */
    #[Route('/admin/product/list', name: 'sc_admin_product_list')]
    public function list(ProductRepository     $productRepository,
                         PaginatorInterface    $paginator,
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
        $query = $searchEntity->getQueryForSelect($request, $productRepository,
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