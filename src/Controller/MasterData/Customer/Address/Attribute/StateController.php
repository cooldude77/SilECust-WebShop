<?php

namespace App\Controller\MasterData\Customer\Address\Attribute;

use App\Form\MasterData\Customer\Address\Attribute\State\DTO\StateDTO;
use App\Form\MasterData\Customer\Address\Attribute\State\StateCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\State\StateEditForm;
use App\Repository\StateRepository;
use App\Service\MasterData\Customer\Address\Attribute\Mapper\State\StateDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StateController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/state/create', 'state_create')]
    public function create(StateDTOMapper $stateDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $stateDTO = new StateDTO();
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
        return $this->render('admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]);
    }


    #[Route('/state/{id}/edit', name: 'state_edit')]
    public function edit(EntityManagerInterface $entityManager,
        StateRepository $stateRepository, StateDTOMapper $stateDTOMapper,
        Request $request, int $id
    ): Response {
        $state = $stateRepository->find($id);


        if (!$state) {
            throw $this->createNotFoundException('No State found for id ' . $id);
        }

        $stateDTO = new StateDTO();
        $stateDTO->id = $id;

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

        return $this->render('admin/ui/panel/section/content/edit/edit.html.twig', ['form' =>
                                                                                        $form]);
    }

    #[Route('/state/{id}/display', name: 'state_display')]
    public function display(StateRepository $stateRepository, int $id): Response
    {
        $state = $stateRepository->find($id);
        if (!$state) {
            throw $this->createNotFoundException('No state found for id ' . $id);
        }

        $displayParams = ['title' => 'State',
                          'link_id' => 'id-state',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'First Name',
                                        'propertyName' => 'firstName',
                                        'link_id' => 'id-display-state'],
                                       ['label' => 'Last Name',
                                        'propertyName' => 'lastName'],]];

        return $this->render(
            'master_data/state/state_display.html.twig',
            ['entity' => $state, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/state/list', name: 'state_list')]
    public function list(StateRepository $stateRepository): Response
    {

        $listGrid = ['title' => 'State',
                     'link_id' => 'id-state',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'firstName',
                                    'action' => 'display',],
                     ],
                     'createButtonConfig' => ['link_id' => ' id-create-state',
                                              'function' => 'state',
                                              'anchorText' => 'create State']];

        $states = $stateRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $states, 'listGrid' => $listGrid]
        );
    }
}