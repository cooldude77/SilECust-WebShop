<?php

namespace App\Controller\MasterData\Customer\Address\Attribute;

use App\Form\MasterData\Customer\Address\Attribute\Country\CountryCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\Country\CountryEditForm;
use App\Form\MasterData\Customer\Address\Attribute\Country\DTO\CountryDTO;
use App\Repository\CountryRepository;
use App\Service\MasterData\Customer\Address\Attribute\Mapper\Country\CountryDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/country/create', 'country_create')]
    public function create(CountryDTOMapper $countryDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
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
        return $this->render('admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]);
    }


    #[Route('/country/{id}/edit', name: 'country_edit')]
    public function edit(EntityManagerInterface $entityManager,
        CountryRepository $countryRepository, CountryDTOMapper $countryDTOMapper,
        Request $request, int $id
    ): Response {
        $country = $countryRepository->find($id);


        if (!$country) {
            throw $this->createNotFoundException('No Country found for id ' . $id);
        }

        $countryDTO = new CountryDTO();
        $countryDTO->id = $id;

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

        return $this->render('admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]);
    }

    #[Route('/country/{id}/display', name: 'country_display')]
    public function display(CountryRepository $countryRepository, int $id): Response
    {
        $country = $countryRepository->find($id);
        if (!$country) {
            throw $this->createNotFoundException('No country found for id ' . $id);
        }

        $displayParams = ['title' => 'Country',
                          'link_id' => 'id-country',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'First Name',
                                        'propertyName' => 'firstName',
                                        'link_id' => 'id-display-country'],
                                       ['label' => 'Last Name',
                                        'propertyName' => 'lastName'],]];

        return $this->render(
            'master_data/country/country_display.html.twig',
            ['entity' => $country, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/country/list', name: 'country_list')]
    public function list(CountryRepository $countryRepository): Response
    {

        $listGrid = ['title' => 'Country',
                     'link_id' => 'id-country',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'firstName',
                                    'action' => 'display',],
                     ],
                     'createButtonConfig' => ['link_id' => ' id-create-country',
                                              'function' => 'country',
                                              'anchorText' => 'create Country']];

        $countries = $countryRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $countries, 'listGrid' => $listGrid]
        );
    }
}