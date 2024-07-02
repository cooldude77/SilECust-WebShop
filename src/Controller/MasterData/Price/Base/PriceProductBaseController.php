<?php
// src/Controller/PriceController.php
namespace App\Controller\MasterData\Price\Base;

// ...
use App\Entity\PriceProductBase;
use App\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use App\Form\MasterData\Price\Mapper\PriceProductBaseDTOMapper;
use App\Form\MasterData\Price\PriceProductBaseCreateForm;
use App\Form\MasterData\Price\PriceProductBaseEditForm;
use App\Repository\PriceProductBaseRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PriceProductBaseController extends AbstractController
{

    #[Route('/price/product/base/create', name: 'price_product_base_create')]
    public function create(PriceProductBaseDTOMapper $mapper, EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $priceProductBaseDTO = new PriceProductBaseDTO();

        $form = $this->createForm(PriceProductBaseCreateForm::class, $priceProductBaseDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $priceProductBase = $mapper->mapDtoToEntity($data);

            $entityManager->persist($priceProductBase);
            $entityManager->flush();

            $this->addFlash(
                'success', "Price created successfully"
            );

            $id = $priceProductBase->getId();
            $this->addFlash(
                'success', "Price created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Price created successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[\Symfony\Component\Routing\Attribute\Route('/price/product/base/{id}/edit', name: 'price_product_base_edit')]
    public function edit(int $id, PriceProductBaseDTOMapper $mapper,
        EntityManagerInterface $entityManager,
        PriceProductBaseRepository $priceProductBaseRepository, Request $request
    ): Response {
        $priceProductBaseDTO = new PriceProductBaseDTO();

        $priceBase = $priceProductBaseRepository->find($id);

        $form = $this->createForm(PriceProductBaseEditForm::class, $priceProductBaseDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $priceBase = $mapper->mapDtoToEntityForEdit(
                $form->getData(), $priceBase
            );

            $entityManager->persist($priceBase);
            $entityManager->flush();

            $this->addFlash(
                'success', "Price Value updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Price Value updated successfully"]
                ), 200
            );
        }

        return $this->render(
            'admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }

    #[Route('/price/product/base/{id}/display', name: 'price_product_base_display')]
    public function display(ProductRepository $productRepository, int $id): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Price',
                          'link_id' => 'id-price',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Name',
                                        'propertyName' => 'name',
                                        'link_id' => 'id-display-price'],
                                       ['label' => 'Description',
                                        'propertyName' => 'description'],]];

        return $this->render(
            'master_data/product/product_display.html.twig',
            ['entity' => $product, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/price/product/base/list', name: 'price_product_base_list')]
    public function list(ProductRepository $productRepository, PaginatorInterface $paginator,
        Request $request
    ):
    Response {

        $listGrid = ['title' => 'Price',
                     'link_id' => 'id-price',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description', 'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => ' id-create-price',
                                              'function' => 'product',
                                              'anchorText' => 'Create Price']];

        $query = $productRepository->getQueryForSelect();

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

    #[Route('/price/product/base/{id}/fetch', name: 'price_product_base_fetch')]
    public function fetch(int $id, ProductRepository $productRepository,
        PriceProductBaseRepository $priceProductBaseRepository
    ):
    Response {

        $product = $productRepository->find($id);
        /** @var PriceProductBase $price */
        $price = $priceProductBaseRepository->findOneBy(['product' => $product]);

        return new JsonResponse(['price' => $price->getPrice(),
                                 'currency' => $price->getCurrency()
                                     ->getSymbol()]);

    }
}