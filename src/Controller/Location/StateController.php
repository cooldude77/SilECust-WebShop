<?php

namespace Silecust\WebShop\Controller\Location;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\State\StateCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\State\StateEditForm;
use Silecust\WebShop\Repository\CountryRepository;
use Silecust\WebShop\Repository\StateRepository;
use Silecust\WebShop\Service\Location\Mapper\State\StateDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StateController extends EnhancedAbstractController
{
    #[Route('/admin/state/country/{id}/create', 'sc_admin_state_create')]
    public function create(CountryRepository      $countryRepository,
                           StateDTOMapper         $stateDTOMapper,
                           EntityManagerInterface $entityManager,
                           Request                $request,
                           int                    $id
    ): Response
    {
        $country = $countryRepository->find($id);
        if (!$country)
            throw $this->createNotFoundException('No country found for id ' . $id);

        $stateDTO = new StateDTO();
        $stateDTO->countryId = $country->getId();

        $form = $this->createForm(
            StateCreateForm::class, $stateDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $stateEntity = $stateDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($stateEntity);
            $entityManager->flush();


            $id = $stateEntity->getId();

            $this->addFlash(
                'success', "State created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "State created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('@SilecustWebShop/location_data/admin/state/state_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/state/{id}/edit', name: 'sc_admin_state_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         StateRepository        $stateRepository,
                         StateDTOMapper         $stateDTOMapper,
                         Request                $request,
                         int                    $id
    ): Response
    {
        $state = $stateRepository->find($id);

        if (!$state) {
            throw $this->createNotFoundException('No State found for id ' . $id);
        }

        $stateDTO = $stateDTOMapper->mapToDTOForEdit($state);


        $form = $this->createForm(StateEditForm::class, $stateDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $state = $stateDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($state);
            $entityManager->flush();

            $id = $state->getId();

            $this->addFlash(
                'success', "State updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "State updated successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/location_data/admin/state/state_edit.html.twig', ['form' =>
            $form]);
    }

    #[Route('/admin/state/{id}/display', name: 'sc_admin_state_display')]
    public function display(StateRepository $stateRepository,
                            Request         $request,
                            int             $id
    ): Response
    {
        $state = $stateRepository->find($id);

        if (!$state) {
            throw $this->createNotFoundException('No State found for id ' . $id);
        }


        $displayParams = ['title' => 'State',
            'link_id' => 'id-state',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'State',
                'propertyName' => 'code',
                'link_id' => 'id-display-state'],
                ['label' => 'Name',
                    'propertyName' => 'name'],]];

        return $this->render(
            '@SilecustWebShop/location_data/admin/state/state_display.html.twig',
            ['entity' => $state, 'params' => $displayParams, 'request' => $request]
        );

    }

    #[Route('/admin/state/country/{id}/list', name: 'sc_admin_state_list')]
    public function list(CountryRepository  $countryRepository,
                         StateRepository    $stateRepository,
                         Request            $request,
                         PaginatorInterface $paginator,
                         int                $id): Response
    {
        $this->setContentHeading($request, 'State');

        $country = $countryRepository->find($id);
        if (!$country)
            throw $this->createNotFoundException('No country found for id ' . $id);

        $listGrid = ['title' => 'State',
            'link_id' => 'id-state',
            'edit_link_allowed' => true,
            'function' => 'state',
            'columns' => [['label' => 'State',
                'propertyName' => 'code',
                'action' => 'display',],
            ],
            'createButtonConfig' => [
                'link_id' => ' id-create-state',
                'id' => $country->getId(),
                'function' => 'state',
                'anchorText' => 'create State']];


        $query = $stateRepository->getQueryForSelect($country);

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