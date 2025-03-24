<?php

namespace Silecust\WebShop\Controller\Location;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\Country\CountryCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\Country\CountryEditForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Service\Location\Mapper\Country\CountryDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountryController extends EnhancedAbstractController
{
    #[Route('/admin/country/create', name: 'sc_admin_country_create')]
    public function create(CountryDTOMapper       $countryDTOMapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $countryDTO = new CountryDTO();
        $form = $this->createForm(
            CountryCreateForm::class, $countryDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $countryEntity = $countryDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($countryEntity);
            $entityManager->flush();


            $id = $countryEntity->getId();

            $this->addFlash(
                'success', "Country created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Country created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('@SilecustWebShop/location_data/admin/country/country_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/country/{id}/edit', name: 'sc_admin_country_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         CountryRepository      $countryRepository, CountryDTOMapper $countryDTOMapper,
                         Request                $request, int $id
    ): Response
    {
        $country = $countryRepository->find($id);

        if (!$country) {
            throw $this->createNotFoundException('No Country found for id ' . $id);
        }

        $countryDTO = $countryDTOMapper->mapToDTOForEdit($country);

        $form = $this->createForm(CountryEditForm::class, $countryDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $country = $countryDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($country);
            $entityManager->flush();

            $id = $country->getId();

            $this->addFlash(
                'success', "Country updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Country updated successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/location_data/admin/country/country_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/country/{id}/display', name: 'sc_admin_country_display')]
    public function display(CountryRepository $countryRepository, int $id, Request $request): Response
    {
        $country = $countryRepository->find($id);
        if (!$country) {
            throw $this->createNotFoundException('No country found for id ' . $id);
        }

        $displayParams = ['title' => 'Country',
            'link_id' => 'id-country',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'Country Code',
                'propertyName' => 'code',
                'link_id' => 'id-display-country'],
                ['label' => 'Country Name',
                    'propertyName' => 'name'],]];

        return $this->render(
            '@SilecustWebShop/location_data/admin/country/country_display.html.twig',
            ['request' => $request, 'entity' => $country, 'params' => $displayParams]
        );

    }

    #[Route('/admin/country/list', name: 'sc_admin_country_list')]
    public function list(CountryRepository $countryRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $listGrid = ['title' => 'Country',
            'link_id' => 'id-country',
            'edit_link_allowed' => true,
            'columns' => [['label' => 'Name',
                'propertyName' => 'name',
                'action' => 'display',],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-country',
                'function' => 'country',
                'anchorText' => 'create Country']];

        $query = $countryRepository->getQueryForSelect();

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