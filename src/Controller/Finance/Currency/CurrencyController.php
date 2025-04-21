<?php
// src/Controller/LuckyController.php
namespace Silecust\WebShop\Controller\Finance\Currency;

use Knp\Component\Pager\PaginatorInterface;
use Silecust\WebShop\Entity\Country;
use Silecust\WebShop\Entity\Currency;
use Silecust\WebShop\Form\Finance\Currency\CurrencyCreateForm;
use Silecust\WebShop\Form\Finance\Currency\CurrencyEditForm;
use Silecust\WebShop\Form\Finance\Currency\DTO\CurrencyDTO;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Repository\CurrencyRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\Finance\Currency\Mapper\CurrencyDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CurrencyController extends EnhancedAbstractController
{

    #[Route('/admin/currency/create', name: 'sc_admin_currency_create')]
    public function create(
        CountryRepository $countryRepository,
        CurrencyDTOMapper      $currencyDTOMapper,
        EntityManagerInterface $entityManager,
        Request                $request,
        ValidatorInterface     $validator
    ): Response
    {
        $currencyDTO = new CurrencyDTO();

        $form = $this->createForm(CurrencyCreateForm::class, $currencyDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $currencyEntity = $currencyDTOMapper->mapToEntityForCreate($form->getData());

            $errors = $validator->validate($currencyEntity);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($currencyEntity);
                $entityManager->flush();

                $this->addFlash('success', "Currency created successfully");
                return new Response(
                    serialize(
                        ['id' => $currencyEntity->getId(), 'message' => "Currency created successfully"]
                    ), 200
                );
            }
        }

        return $this->render(
            '@SilecustWebShop/finance/currency/currency_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/currency/{id}/edit', name: 'sc_admin_currency_edit')]
    public function edit(
        Currency               $currency,
        EntityManagerInterface $entityManager,
        CurrencyDTOMapper      $currencyDTOMapper,
        Request                $request,
        ValidatorInterface     $validator
    ): Response
    {
        $currencyDTO = $currencyDTOMapper->mapToDtoFromEntity($currency);

        $form = $this->createForm(CurrencyEditForm::class, $currencyDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $currency = $currencyDTOMapper->mapToEntityForEdit($data);

            $errors = $validator->validate($currency);

            if (count($errors) == 0) {
                // perform some action...
                $entityManager->persist($currency);
                $entityManager->flush();

                $this->addFlash('success', "Currency created successfully");
                return new Response(
                    serialize(
                        ['id' => $currency->getId(), 'message' => "Currency created successfully"]
                    ), 200
                );
            }
        }
        return $this->render(
            '@SilecustWebShop/finance/currency/currency_edit.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/currency/{id}/display', name: 'sc_admin_currency_display')]
    public function display(Currency $currency,
                            Request  $request): Response
    {

        $displayParams = ['title' => 'Currency',
            'editButtonLinkText' => 'Edit',
            'link_id' => 'id-currency',
            'fields' => [['label' => 'Code',
                'propertyName' => 'code',
                'link_id' => 'id-display-currency',],
                ['label' => 'Symbol',
                    'propertyName' => 'symbol'],]];

        return $this->render(
            '@SilecustWebShop/finance/currency/currency_display.html.twig',
            ['request' => $request, 'entity' => $currency, 'params' => $displayParams]
        );

    }

    #[Route('/admin/currency/list', name: 'sc_admin_currency_list')]
    public function list(CurrencyRepository $currencyRepository,
                         PaginatorInterface    $paginator,
                         SearchEntityInterface $searchEntity,
                         Request               $request): Response
    {

        $listGrid = ['title' => 'Currency',
            'link_id' => 'id-currency',
            'columns' => [['label' => 'Code',
                'propertyName' => 'Code',
                'action' => 'display',],
                ['label' => 'Symbol',
                    'propertyName' => 'symbol'],],
            'createButtonConfig' => ['link_id' => 'id-create-currency',
                'function' => 'currency',
                'anchorText' => 'Create Currency']];

        $query = $searchEntity->getQueryForSelect($request, $productRepository);

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