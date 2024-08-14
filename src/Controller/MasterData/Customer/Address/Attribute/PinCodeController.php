<?php

namespace App\Controller\MasterData\Customer\Address\Attribute;

use App\Form\MasterData\Customer\Address\Attribute\PinCode\PinCodeCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\PinCode\PinCodeEditForm;
use App\Form\MasterData\Customer\Address\Attribute\PinCode\DTO\PinCodeDTO;
use App\Repository\PinCodeRepository;
use App\Service\MasterData\Customer\Address\Attribute\Mapper\PinCode\PinCodeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinCodeController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/postal_code/create', 'pinCode_create')]
    public function create(PinCodeDTOMapper $pinCodeDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $pinCodeDTO = new PinCodeDTO();
        $form = $this->createForm(
            PinCodeCreateForm::class, $pinCodeDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $pinCodeEntity = $pinCodeDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($pinCodeEntity);
            $entityManager->flush();


            $id = $pinCodeEntity->getId();

            $this->addFlash(
                'success', "PinCode created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "PinCode created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render(
            '/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );
    }


    #[Route('/postal_code/{id}/edit', name: 'pinCode_edit')]
    public function edit(EntityManagerInterface $entityManager,
        PinCodeRepository $pinCodeRepository, PinCodeDTOMapper $pinCodeDTOMapper,
        Request $request, int $id
    ): Response {
        $pinCode = $pinCodeRepository->find($id);


        if (!$pinCode) {
            throw $this->createNotFoundException('No PinCode found for id ' . $id);
        }

        $pinCodeDTO = new PinCodeDTO();
        $pinCodeDTO->id = $id;

        $form = $this->createForm(PinCodeEditForm::class, $pinCodeDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pinCode = $pinCodeDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($pinCode);
            $entityManager->flush();

            $id = $pinCode->getId();

            $this->addFlash(
                'success', "PinCode updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "PinCode updated successfully"]
                ), 200
            );
        }

        return $this->render('admin/ui/panel/section/content/edit/edit.html.twig', ['form' =>
                                                                                        $form]
        );
    }

    #[Route('/postal_code/{id}/display', name: 'pinCode_display')]
    public function display(PinCodeRepository $pinCodeRepository, int $id): Response
    {
        $pinCode = $pinCodeRepository->find($id);
        if (!$pinCode) {
            throw $this->createNotFoundException('No pinCode found for id ' . $id);
        }

        $displayParams = ['title' => 'PinCode',
                          'link_id' => 'id-pinCode',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'First Name',
                                        'propertyName' => 'firstName',
                                        'link_id' => 'id-display-pinCode'],
                                       ['label' => 'Last Name',
                                        'propertyName' => 'lastName'],]];

        return $this->render(
            'master_data/postal_code/postal_code_display.html.twig',
            ['entity' => $pinCode, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/postal_code/list', name: 'postal_code_list')]
    public function list(PinCodeRepository $pinCodeRepository): Response
    {

        $listGrid = ['title' => 'PinCode',
                     'link_id' => 'id-pinCode',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'firstName',
                                    'action' => 'display',],
                     ],
                     'createButtonConfig' => ['link_id' => ' id-create-pinCode',
                                              'function' => 'pinCode',
                                              'anchorText' => 'create PinCode']];

        $pinCodes = $pinCodeRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $pinCodes, 'listGrid' => $listGrid]
        );
    }
}