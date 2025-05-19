<?php

namespace Silecust\WebShop\Controller\Location;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\City;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\CityCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\CityEditForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\City\DTO\CityDTO;
use Silecust\WebShop\Repository\CityRepository;
use Silecust\WebShop\Repository\StateRepository;
use Silecust\WebShop\Service\Location\Mapper\City\CityDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends EnhancedAbstractController
{
    #[Route('/admin/state/{id}/city/create', name: 'sc_admin_city_create')]
    public function create(StateRepository        $stateRepository,
                           CityDTOMapper          $cityDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           int                    $id
    ): Response
    {
        $state = $stateRepository->find($id);

        if (!$state) {
            throw $this->createNotFoundException('No State found for id ' . $id);
        }


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
            '@SilecustWebShop/location_data/admin/city/city_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/city/{id}/edit', name: 'sc_admin_city_edit')]
    public function edit(
        EntityManagerInterface $entityManager,
        CityRepository         $cityRepository,
        CityDTOMapper          $cityDTOMapper,
        Request                $request,
        int                    $id
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
     * @param string $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/city/{id}/display', name: 'sc_admin_city_display')]
    public function display(
        CityRepository $cityRepository,
        Request        $request,
        int $id): Response
    {

        $city = $cityRepository->find($id);

        if (!$city) {
            throw $this->createNotFoundException('No City found for id ' . $id);
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
            '@SilecustWebShop/location_data/admin/city/city_display.html.twig',
            ['request' => $request, 'entity' => $city, 'params' => $displayParams]
        );

    }

    #[Route('/admin/state/{id}/city/list', name: 'sc_admin_city_list')]
    public function list(int $id, StateRepository $stateRepository,
                         CityRepository $cityRepository,
                         Request        $request, PaginatorInterface $paginator): Response
    {
        $this->setContentHeading($request, 'City');

        $state = $stateRepository->find($id);

        if (!$state) {
            throw $this->createNotFoundException('No State found for id ' . $id);
        }

        $listGrid = ['title' => 'City',
            'link_id' => 'id-city',
            'function' => 'city',
            'edit_link_allowed' => true,
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