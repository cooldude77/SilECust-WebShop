<?php
// src/Controller/PriceController.php
namespace Silecust\WebShop\Controller\MasterData\Price\Discount;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\PriceProductDiscount;
use Silecust\WebShop\Form\MasterData\Price\Discount\DTO\PriceProductDiscountDTO;
use Silecust\WebShop\Form\MasterData\Price\Discount\Mapper\PriceProductDiscountDTOMapper;
use Silecust\WebShop\Form\MasterData\Price\Discount\PriceProductDiscountCreateForm;
use Silecust\WebShop\Form\MasterData\Price\Discount\PriceProductDiscountEditForm;
use Silecust\WebShop\Repository\PriceProductDiscountRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PriceProductDiscountController extends EnhancedAbstractController
{

    #[Route('/admin/price/product/discount/create', name: 'sc_admin_price_product_discount_create')]
    public function create(PriceProductDiscountDTOMapper $mapper, EntityManagerInterface $entityManager,
                           Request                       $request
    ): Response
    {
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
            '@SilecustWebShop/master_data/price/discount/price_product_discount_create.html.twig', ['form' => $form]
        );


    }


    #[\Symfony\Component\Routing\Attribute\Route('/admin/price/product/discount/{id}/edit', name: 'sc_admin_price_product_discount_edit')]
    public function edit(int                            $id,
                         PriceProductDiscountDTOMapper  $mapper,
                         EntityManagerInterface         $entityManager,
                         PriceProductDiscountRepository $priceProductDiscountRepository, Request $request
    ): Response
    {

        $priceDiscount = $priceProductDiscountRepository->find($id);

        $form = $this->createForm(PriceProductDiscountEditForm::class, $mapper->mapToDtoFromEntityForEdit($priceProductDiscountRepository->find($id)));

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
            '@SilecustWebShop/master_data/price/discount/price_product_discount_edit.html.twig',
            ['form' => $form]
        );


    }

    #[Route('/admin/price/product/discount/{id}/display', name: 'sc_admin_price_product_discount_display')]
    public function display(PriceProductDiscountRepository $priceProductDiscountRepository, Request $request,
                            int                            $id
    ): Response
    {
        $priceProductDiscount = $priceProductDiscountRepository->find($id);
        if (!$priceProductDiscount) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Price',
            'link_id' => 'id-price',
            'editButtonLinkText' => 'Edit',
            'fields' => [
                ['label' => 'Discount',
                    'propertyName' => 'value',]
                ,
                ['label' => 'Product',
                    'propertyName' => 'product',
                ]]
        ];

        return $this->render(
            '@SilecustWebShop/master_data/price/discount/price_product_discount_display.html.twig',
            ['entity' => $priceProductDiscount, 'request' => $request, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/admin/price/product/discount/list', name: 'sc_admin_price_product_discount_list')]
    public function list(PriceProductDiscountRepository $priceProductDiscountRepository,
                         PaginatorInterface             $paginator,
                         Request                        $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Product Discounts');

        $listGrid = ['title' => 'Price',
            'link_id' => 'id-price',
            'function' => 'price_product_discount',
            'columns' => [
                ['label' => 'Discount',
                    'propertyName' => 'value',
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
            10 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }

    #[Route('/admin/price/product/discount/{id}/fetch', name: 'sc_admin_price_product_discount_fetch')]
    public function fetch(int                            $id, ProductRepository $productRepository,
                          PriceProductDiscountRepository $priceProductDiscountRepository
    ):
    Response
    {

        $product = $productRepository->find($id);
        /** @var PriceProductDiscount $price */
        $price = $priceProductDiscountRepository->findOneBy(['product' => $product]);

        return new JsonResponse(['price' => $price->getValue(),
            'currency' => $price->getCurrency()
                ->getSymbol()]);

    }
}