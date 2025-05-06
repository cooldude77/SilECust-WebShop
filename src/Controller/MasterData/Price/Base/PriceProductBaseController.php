<?php
// src/Controller/PriceController.php
namespace Silecust\WebShop\Controller\MasterData\Price\Base;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Form\MasterData\Price\DTO\PriceProductBaseDTO;
use Silecust\WebShop\Form\MasterData\Price\Mapper\PriceProductBaseDTOMapper;
use Silecust\WebShop\Form\MasterData\Price\PriceProductBaseCreateForm;
use Silecust\WebShop\Form\MasterData\Price\PriceProductBaseEditForm;
use Silecust\WebShop\Repository\PriceProductBaseRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PriceProductBaseController extends EnhancedAbstractController
{

    #[Route('/admin/price/product/base/create', name: 'sc_admin_price_product_base_create')]
    public function create(PriceProductBaseDTOMapper $mapper, EntityManagerInterface $entityManager,
                           Request                   $request
    ): Response
    {
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
            '@SilecustWebShop/master_data/price/base_product/price_product_base_create.html.twig', ['form' => $form]
        );


    }


    #[\Symfony\Component\Routing\Attribute\Route('/admin/price/product/base/{id}/edit', name: 'sc_admin_price_product_base_edit')]
    public function edit(int                        $id, PriceProductBaseDTOMapper $mapper,
                         EntityManagerInterface     $entityManager,
                         PriceProductBaseRepository $priceProductBaseRepository, Request $request
    ): Response
    {

        $priceBase = $priceProductBaseRepository->find($id);

        $form = $this->createForm(PriceProductBaseEditForm::class, $mapper->maptoDtoFromEntityForEdit($priceBase));

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
            '@SilecustWebShop/master_data/price/base_product/price_product_base_edit.html.twig',
            ['form' => $form]
        );


    }

    #[Route('/admin/price/product/base/{id}/display', name: 'sc_admin_price_product_base_display')]
    public function display(PriceProductBaseRepository $priceProductBaseRepository, int $id
    ): Response
    {
        $priceProductBase = $priceProductBaseRepository->find($id);
        if (!$priceProductBase) {
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
            '@SilecustWebShop/master_data/price/base_product/price_product_base_display.html.twig',
            ['entity' => $priceProductBase, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/admin/price/product/base/list', name: 'sc_admin_price_product_base_list')]
    public function list(PriceProductBaseRepository $priceProductBaseRepository,
                         PaginatorInterface         $paginator,
                         Request                    $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Product Base Prices');

        $listGrid = ['title' => 'Price',
            'link_id' => 'id-price',
            'function' => 'price_product_base',
            'columns' => [
                ['label' => 'Price',
                    'propertyName' => 'price',
                    'action' => 'display'],
                ['label' => 'Product',
                    'propertyName' => 'product',
                ],

            ],
            'createButtonConfig' => ['link_id' => ' id-create-price',
                'function' => 'price_product_base',
                'anchorText' => 'Create Price']
        ];

        $query = $priceProductBaseRepository->getQueryForSelect();


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

    /**
     * @throws PriceProductBaseNotFound
     */
    #[Route('/admin/price/product/base/{id}/fetch', name: 'sc_admin_price_product_base_fetch')]
    public function fetch(int                        $id, ProductRepository $productRepository,
                          PriceProductBaseRepository $priceProductBaseRepository
    ):
    Response
    {

        $product = $productRepository->find($id);
        /** @var PriceProductBase $price */
        $price = $priceProductBaseRepository->findOneBy(['product' => $product]);

        if ($price == null)
            throw new PriceProductBaseNotFound($product);

        return new JsonResponse(['price' => $price->getPrice(),
            'currency' => $price->getCurrency()
                ->getSymbol()]);

    }
}