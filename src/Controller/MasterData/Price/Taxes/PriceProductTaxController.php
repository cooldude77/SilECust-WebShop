<?php
// src/Controller/PriceController.php
namespace Silecust\WebShop\Controller\MasterData\Price\Taxes;

// ...
use Silecust\WebShop\Form\MasterData\Price\Tax\DTO\PriceProductTaxDTO;
use Silecust\WebShop\Form\MasterData\Price\Tax\Mapper\PriceProductTaxDTOMapper;
use Silecust\WebShop\Form\MasterData\Price\Tax\PriceProductTaxCreateForm;
use Silecust\WebShop\Form\MasterData\Price\Tax\PriceProductTaxEditForm;
use Silecust\WebShop\Repository\PriceProductTaxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PriceProductTaxController extends EnhancedAbstractController
{


    #[Route('/admin/tax-slab/create', name: 'sc_admin_price_product_tax_create')]
    public function create(PriceProductTaxDTOMapper $priceProductTaxDTOMapper,
                           EntityManagerInterface   $entityManager,
                           Request                  $request,
                           ValidatorInterface       $validator
    ): Response
    {
        $priceProductTaxDTO = new PriceProductTaxDTO();
        $form = $this->createForm(PriceProductTaxCreateForm::class, $priceProductTaxDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $priceProductTaxEntity = $priceProductTaxDTOMapper->mapDtoToEntityForCreate($form->getData());

            $errors = $validator->validate($priceProductTaxEntity);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($priceProductTaxEntity);
                $entityManager->flush();

                $this->addFlash('success', "PriceProductTax created successfully");
                return new Response(
                    serialize(
                        ['id' => $priceProductTaxEntity->getId(), 'message' => "PriceProductTax created successfully"]
                    ), 200
                );
            }
        }

        return $this->render(
            '@SilecustWebShop/master_data/price/tax/price_product_tax_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/tax-slab/{id}/edit', name: 'sc_admin_price_product_tax_edit')]
    public function edit(EntityManagerInterface    $entityManager,
                         PriceProductTaxRepository $priceProductTaxRepository,
                         PriceProductTaxDTOMapper  $priceProductTaxDTOMapper,
                         Request                   $request, int $id,
                         ValidatorInterface        $validator
    ): Response
    {
        $priceProductTax = $priceProductTaxRepository->find($id);


        if (!$priceProductTax) {
            throw $this->createNotFoundException(
                'No priceProductTax found for id ' . $id
            );
        }
        $priceProductTaxDTO = $priceProductTaxDTOMapper->mapToDtoFromEntityForEdit($priceProductTax);

        $form = $this->createForm(PriceProductTaxEditForm::class, $priceProductTaxDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $priceProductTax = $priceProductTaxDTOMapper->mapDtoToEntityForEdit($data);

            $errors = $validator->validate($priceProductTax);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($priceProductTax);
                $entityManager->flush();

                $this->addFlash('success', "PriceProductTax created successfully");
                return new Response(
                    serialize(
                        ['id' => $priceProductTax->getId(), 'message' => "PriceProductTax created successfully"]
                    ), 200
                );
            }
        }
        return $this->render(
            '@SilecustWebShop/master_data/price/tax/price_product_tax_edit.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/tax-slab/{id}/display', name: 'sc_admin_price_product_tax_display')]
    public function display(PriceProductTaxRepository $priceProductTaxRepository, int $id, Request $request): Response
    {
        $priceProductTax = $priceProductTaxRepository->find($id);
        if (!$priceProductTax) {
            throw $this->createNotFoundException(
                'No priceProductTax found for id ' . $id
            );
        }

        $displayParams = ['title' => 'PriceProductTax',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-priceProductTax',
            'fields' => [['label' => 'Name',
                'propertyName' => 'name',
                'link_id' => 'id-display-priceProductTax',],
                ['label' => 'Description',
                    'propertyName' => 'description'],]];

        return $this->render(
            '@SilecustWebShop/master_data/price/tax/price_product_tax_display.html.twig',
            ['request' => $request, 'entity' => $priceProductTax, 'params' => $displayParams]
        );

    }

    #[Route('/admin/price/product/tax/list', name: 'sc_admin_price_product_tax_list')]
    public function list(PriceProductTaxRepository $priceProductTaxRepository,
                         PaginatorInterface        $paginator,
                         Request                   $request
    ):
    Response
    {
        $this->setContentHeading($request, 'Product Taxes');

        $listGrid = ['title' => 'Tax',
            'link_id' => 'id-price-tax',
            'function' => 'price_product_tax',
            'columns' => [
                /*   ['label' => 'Tax Slab',
                       'propertyName' => 'taxRate',
                       'action' => 'display'],
                  */
                ['label' => 'Product',
                    'propertyName' => 'product',
                ],

            ],
            'createButtonConfig' => ['link_id' => ' id-create-tax',
                'function' => 'price_product_tax',
                'anchorText' => 'Create Tax']];

        $query = $priceProductTaxRepository->getQueryForSelect();


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