<?php
// src/Controller/PriceController.php
namespace App\Controller\MasterData\Price\Discount;

// ...
use App\Entity\PriceProducDiscount;
use App\Form\MasterData\Price\DTO\PriceProducDiscountDTO;
use App\Form\MasterData\Price\Mapper\PriceProducDiscountDTOMapper;
use App\Form\MasterData\Price\PriceProducDiscountCreateForm;
use App\Form\MasterData\Price\PriceProducDiscountEditForm;
use App\Repository\PriceProducDiscountRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PriceProductDiscountController extends AbstractController
{

    #[Route('/price/product/discount/create', name: 'price_product_discount_create')]
    public function create(PriceProducDiscountDTOMapper $mapper, EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $priceProducDiscountDTO = new PriceProducDiscountDTO();

        $form = $this->createForm(PriceProducDiscountCreateForm::class, $priceProducDiscountDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $priceProducDiscount = $mapper->mapDtoToEntity($data);

            $entityManager->persist($priceProducDiscount);
            $entityManager->flush();

            $this->addFlash(
                'success', "Price created successfully"
            );

            $id = $priceProducDiscount->getId();
            $this->addFlash(
                'success', "Price created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Price created successfully"]
                ), 200
            );
        }

        return $this->render('master_data/price/discount_product/price_product_discount_create.html.twig', ['form' => $form]);


    }


    #[\Symfony\Component\Routing\Attribute\Route('/price/product/discount/{id}/edit', name: 'price_product_discount_edit')]
    public function edit(int $id, PriceProducDiscountDTOMapper $mapper,
        EntityManagerInterface $entityManager,
        PriceProducDiscountRepository $priceProducDiscountRepository, Request $request
    ): Response {
        $priceProducDiscountDTO = new PriceProducDiscountDTO();

        $priceBase = $priceProducDiscountRepository->find($id);

        $form = $this->createForm(PriceProducDiscountEditForm::class, $priceProducDiscountDTO);

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

        return $this->render('master_data/price/discount_product/price_product_discount_edit.html.twig',
            ['form' => $form]);


    }

    #[Route('/price/product/discount/{id}/display', name: 'price_product_discount_display')]
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
            'master_data/price/discount_product/price_product_discount_display.html.twig',
            ['entity' => $product, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/price/product/discount/list', name: 'price_product_discount_list')]
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
                                              'function' => 'price_product_discount',
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

    #[Route('/price/product/discount/{id}/fetch', name: 'price_product_discount_fetch')]
    public function fetch(int $id, ProductRepository $productRepository,
        PriceProducDiscountRepository $priceProducDiscountRepository
    ):
    Response {

        $product = $productRepository->find($id);
        /** @var PriceProducDiscount $price */
        $price = $priceProducDiscountRepository->findOneBy(['product' => $product]);

        return new JsonResponse(['price' => $price->getPrice(),
                                 'currency' => $price->getCurrency()
                                     ->getSymbol()]);

    }
}