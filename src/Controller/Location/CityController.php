<?php

namespace Silecust\WebShop\Controller\Location;

use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Entity\State;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\CityCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\CityEditForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use Silecust\WebShop\Repository\CityRepository;
use Silecust\WebShop\Service\Location\Mapper\City\CityDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends EnhancedAbstractController
{
    #[Route('/admin/city/state/{id}/create', 'sc_route_admin_city_create')]
    public function create(State                  $state,
                           CityDTOMapper          $cityDTOMapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $cityDTO = new CityDTO();
        $cityDTO->stateId = $state->getId();

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
    public function edit(City                   $city,
                         EntityManagerInterface $entityManager,
                         CityRepository         $cityRepository, CityDTOMapper $cityDTOMapper,
                         Request                $request, int $id
    ): Response
    {

        $cityDTO = $cityDTOMapper->mapToDTOForEdit($city);

        $form = $this->createForm(CityEditForm::class, $cityDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var CityDTO $data */
            $data = $form->getData();

            $city = $cityDTOMapper->mapToEntityForEdit($data);
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

        return $this->render('@SilecustWebShop/location_data/admin/city/city_edit.html.twig', ['form' =>
            $form]);
    }

    /**
     * @param City $city
     * @param CityRepository $cityRepository
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/city/{id}/display', name: 'sc_route_admin_city_display')]
    public function display(City $city, Request $request): Response
    {

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
            ['request' => $request, 'entity' => $city, 'params' => $displayParams]
        );

    }

    #[Route('/admin/city/state/{id}/list', name: 'sc_route_admin_city_list')]
    public function list(State $state, CityRepository $cityRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $listGrid = ['title' => 'City',
            'link_id' => 'id-city',
            'function' => 'city',
            'edit_link_allowed'=>true,
            'columns' => [['label' => 'Code',
                'propertyName' => 'code',
                'action' => 'display',],
            ],
            'createButtonConfig' => [
                'link_id' => ' id-create-city',
                'id' => $state->getId(),
                'function' => 'city',
                'anchorText' => 'create City']];

        $query = $cityRepository->getQueryForSelect($state);

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