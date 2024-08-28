<?php

namespace App\Controller\Location;

use App\Form\MasterData\Customer\Address\Attribute\Country\CountryCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\Country\CountryEditForm;
use App\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;
use App\Repository\CountryRepository;
use App\Service\Location\Mapper\Country\CountryDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountryController extends AbstractController
{
    #[Route('/admin/country/create', 'sc_route_admin_country_create')]
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
        return $this->render('location_data/admin/country/country_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/country/{id}/edit', name: 'sc_route_admin_country_edit')]
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

        return $this->render('location_data/admin/country/country_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/country/{id}/display', name: 'sc_route_admin_country_display')]
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
            'location_data/admin/country/country_display.html.twig',
            ['request' => $request, 'entity' => $country, 'params' => $displayParams]
        );

    }

    #[Route('/admin/country/list', name: 'sc_route_admin_country_list')]
    public function list(CountryRepository $countryRepository, Request $request): Response
    {

        $listGrid = ['title' => 'Country',
            'link_id' => 'id-country',
            'columns' => [['label' => 'Name',
                'propertyName' => 'name',
                'action' => 'display',],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-country',
                'function' => 'country',
                'anchorText' => 'create Country']];

        $countries = $countryRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $countries, 'listGrid' => $listGrid]
        );
    }
}