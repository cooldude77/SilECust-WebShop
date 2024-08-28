<?php

namespace App\Controller\Location;

use App\Entity\State;
use App\Form\MasterData\Customer\Address\Attribute\City\CityCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\City\CityEditForm;
use App\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use App\Repository\CityRepository;
use App\Repository\StateRepository;
use App\Service\Location\Mapper\City\CityDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends AbstractController
{
    #[Route('/admin/city/create', 'sc_route_admin_city_create')]
    public function create(CityDTOMapper          $cityDTOMapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $cityDTO = new CityDTO();
        $form = $this->createForm(
            CityCreateForm::class, $cityDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CityDTO $data */
            $data = $form->getData();
            $data->stateId = $form->get('state')->getData()->getId();

            $cityEntity = $cityDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($cityEntity);
            $entityManager->flush();


            $id = $cityEntity->getId();

            $this->addFlash(
                'success', "City created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "City created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render(
            'location_data/admin/city/city_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/city/{id}/edit', name: 'sc_route_admin_city_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         CityRepository         $cityRepository, CityDTOMapper $cityDTOMapper,
                         Request                $request, int $id
    ): Response
    {

        $city = $cityRepository->find($id);


        if (!$city) {
            throw $this->createNotFoundException('No City found for id ' . $id);
        }

        $cityDTO = $cityDTOMapper->mapToDTOForEdit($city);

        $form = $this->createForm(CityEditForm::class, $cityDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var CityDTO $data */
            $data = $form->getData();
            $data->stateId = $form->get('state')->getData()->getId();

            $city = $cityDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($city);
            $entityManager->flush();

            $id = $city->getId();

            $this->addFlash(
                'success', "City updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "City updated successfully"]
                ), 200
            );
        }

        return $this->render('location_data/admin/city/city_edit.html.twig', ['form' =>
            $form]);
    }

    #[Route('/admin/city/{id}/display', name: 'sc_route_admin_city_display')]
    public function display(CityRepository $cityRepository, int $id,Request $request): Response
    {
        $city = $cityRepository->find($id);
        if (!$city) {
            throw $this->createNotFoundException('No city found for id ' . $id);
        }

        $displayParams = ['title' => 'City',
            'link_id' => 'id-city',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'City Code',
                'propertyName' => 'code',
                'link_id' => 'id-display-city'],
                ['label' => 'Name',
                    'propertyName' => 'name'],]];

        return $this->render(
            'location_data/admin/city/city_display.html.twig',
            ['request'=>$request,'entity' => $city, 'params' => $displayParams]
        );

    }

    #[Route('/admin/city/state/{id}/list', name: 'sc_route_admin_city_list')]
    public function list(State $state, CityRepository $cityRepository, Request $request): Response
    {

        $listGrid = ['title' => 'City',
            'link_id' => 'id-city',
            'function'=>'city',
            'columns' => [['label' => 'Code',
                'propertyName' => 'code',
                'action' => 'display',],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-city',
                'function' => 'city',
                'anchorText' => 'create City']];

        $citys = $cityRepository->findAll(['state'=>$state]);
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $citys, 'listGrid' => $listGrid]
        );
    }
}