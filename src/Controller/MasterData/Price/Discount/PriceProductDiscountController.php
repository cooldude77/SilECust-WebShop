<?php
// src/Controller/PriceController.php
namespace App\Controller\MasterData\Price\Discount;

// ...
use App\Entity\PriceProductDiscount;
use App\Form\MasterData\Price\Discount\DTO\PriceProductDiscountDTO;
use App\Form\MasterData\Price\Discount\Mapper\PriceProductDiscountDTOMapper;
use App\Form\MasterData\Price\Discount\PriceProductDiscountCreateForm;
use App\Form\MasterData\Price\Discount\PriceProductDiscountEditForm;
use App\Repository\PriceProductDiscountRepository;
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
    public function create(PriceProductDiscountDTOMapper $mapper, EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $priceProductDiscountDTO = new PriceProductDiscountDTO();

        $form = $this->createForm(PriceProductDiscountCreateForm::class, $priceProductDiscountDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $priceProductDiscount = $mapper->mapDtoToEntity($data);

            $entityManager->persist($priceProductDiscount);
            $entityManager->flush();

            $this->addFlash(
                'success', "Price created successfully"
            );

            $id = $priceProductDiscount->getId();
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
            'master_data/price/discount/price_product_discount_create.html.twig', ['form' => $form]
        );


    }


    #[\Symfony\Component\Routing\Attribute\Route('/price/product/discount/{id}/edit', name: 'price_product_discount_edit')]
    public function edit(int $id, PriceProductDiscountDTOMapper $mapper,
        EntityManagerInterface $entityManager,
        PriceProductDiscountRepository $priceProductDiscountRepository, Request $request
    ): Response {
        $priceProductDiscountDTO = new PriceProductDiscountDTO();

        $priceDiscount = $priceProductDiscountRepository->find($id);

        $form = $this->createForm(PriceProductDiscountEditForm::class, $priceProductDiscountDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $priceDiscount = $mapper->mapDtoToEntityForEdit(
                $form->getData(), $priceDiscount
            );

            $entityManager->persist($priceDiscount);
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
            'master_data/price/discount/price_product_discount_edit.html.twig',
            ['form' => $form]
        );


    }

    #[Route('/price/product/discount/{id}/display', name: 'price_product_discount_display')]
    public function display(PriceProductDiscountRepository $priceProductDiscountRepository, int $id
    ): Response {
        $priceProductDiscount = $priceProductDiscountRepository->find($id);
        if (!$priceProductDiscount) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Price',
                          'link_id' => 'id-price',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [
                              ['label' => 'Price',
                               'propertyName' => 'price',]
                              ,
                              ['label' => 'Product',
                               'propertyName' => 'product',
                              ]]
        ];

        return $this->render(
            'master_data/price/discount/price_product_discount_display.html.twig',
            ['entity' => $priceProductDiscount, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/price/product/discount/list', name: 'price_product_discount_list')]
    public function list(PriceProductDiscountRepository $priceProductDiscountRepository,
        PaginatorInterface $paginator,
        Request $request
    ):
    Response {

        $listGrid = ['title' => 'Price',
                     'link_id' => 'id-price',
                     'columns' => [
                         ['label' => 'Price',
                          'propertyName' => 'price',
                          'action' => 'display'],
                         ['label' => 'Product',
                          'propertyName' => 'product',
                         ],

                     ],
                     'createButtonConfig' => ['link_id' => ' id-create-price',
                                              'function' => 'price_product_discount',
                                              'anchorText' => 'Create Price']];

        $query = $priceProductDiscountRepository->getQueryForSelect();


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
        PriceProductDiscountRepository $priceProductDiscountRepository
    ):
    Response {

        $product = $productRepository->find($id);
        /** @var PriceProductDiscount $price */
        $price = $priceProductDiscountRepository->findOneBy(['product' => $product]);

        return new JsonResponse(['price' => $price->getValue(),
                                 'currency' => $price->getCurrency()
                                     ->getSymbol()]);

    }
}