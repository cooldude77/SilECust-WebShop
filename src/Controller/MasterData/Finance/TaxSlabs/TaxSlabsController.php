<?php
// src/Controller/PriceController.php
namespace App\Controller\MasterData\Financial\TaxSlabs;

// ...
use App\Form\MasterData\Price\Tax\DTO\PriceProductTaxDTO;
use App\Form\MasterData\Price\Tax\Mapper\PriceProductTaxDTOMapper;
use App\Form\MasterData\Price\Tax\PriceProductTaxCreateForm;
use App\Form\MasterData\Price\Tax\PriceProductTaxEditForm;
use App\Repository\PriceProductTaxRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TaxSlabsController extends AbstractController
{


    #[\Symfony\Component\Routing\Attribute\Route('/finacial/tax-slab/list', name: 'price_product_tax_list')]
    public function list(PriceProductTaxRepository $priceProductTaxRepository,
                         PaginatorInterface        $paginator,
                         Request                   $request
    ):
    Response
    {

        $listGrid = ['title' => 'Tax Slab',
            'link_id' => 'id-price-tax-slab',
            'function' => 'finance_tax_slab',
            'columns' => [
                ['label' => 'Tax Slab',
                    'propertyName' => 'taxSlab',
                    'action' => 'display'],
                ['label' => 'Product',
                    'propertyName' => 'product',
                ],

            ],
            'createButtonConfig' => ['link_id' => ' id-create-tax-slab',
                'function' => 'price_product_tax',
                'anchorText' => 'Create Tax']];

        $query = $priceProductTaxRepository->getQueryForSelect();


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