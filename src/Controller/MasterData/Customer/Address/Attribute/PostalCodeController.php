<?php

namespace App\Controller\MasterData\Customer\Address\Attribute;

use App\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeCreateForm;
use App\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeEditForm;
use App\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;
use App\Repository\PostalCodeRepository;
use App\Service\MasterData\Customer\Address\Attribute\Mapper\PostalCode\PostalCodeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostalCodeController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route('/postal_code/create', 'postalCode_create')]
    public function create(PostalCodeDTOMapper $postalCodeDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $postalCodeDTO = new PostalCodeDTO();
        $form = $this->createForm(
            PostalCodeCreateForm::class, $postalCodeDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $postalCodeEntity = $postalCodeDTOMapper->mapToEntityForCreate($form->getData());


            // perform some action...
            $entityManager->persist($postalCodeEntity);
            $entityManager->flush();


            $id = $postalCodeEntity->getId();

            $this->addFlash(
                'success', "PostalCode created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "PostalCode created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render(
            '/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );
    }


    #[Route('/postal_code/{id}/edit', name: 'postalCode_edit')]
    public function edit(EntityManagerInterface $entityManager,
        PostalCodeRepository $postalCodeRepository, PostalCodeDTOMapper $postalCodeDTOMapper,
        Request $request, int $id
    ): Response {
        $postalCode = $postalCodeRepository->find($id);


        if (!$postalCode) {
            throw $this->createNotFoundException('No PostalCode found for id ' . $id);
        }

        $postalCodeDTO = new PostalCodeDTO();
        $postalCodeDTO->id = $id;

        $form = $this->createForm(PostalCodeEditForm::class, $postalCodeDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $postalCode = $postalCodeDTOMapper->mapToEntityForEdit($form->getData());
            // perform some action...
            $entityManager->persist($postalCode);
            $entityManager->flush();

            $id = $postalCode->getId();

            $this->addFlash(
                'success', "PostalCode updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "PostalCode updated successfully"]
                ), 200
            );
        }

        return $this->render('admin/ui/panel/section/content/edit/edit.html.twig', ['form' =>
                                                                                        $form]
        );
    }

    #[Route('/postal_code/{id}/display', name: 'postalCode_display')]
    public function display(PostalCodeRepository $postalCodeRepository, int $id): Response
    {
        $postalCode = $postalCodeRepository->find($id);
        if (!$postalCode) {
            throw $this->createNotFoundException('No postalCode found for id ' . $id);
        }

        $displayParams = ['title' => 'PostalCode',
                          'link_id' => 'id-postalCode',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'First Name',
                                        'propertyName' => 'firstName',
                                        'link_id' => 'id-display-postalCode'],
                                       ['label' => 'Last Name',
                                        'propertyName' => 'lastName'],]];

        return $this->render(
            'master_data/postal_code/postal_code_display.html.twig',
            ['entity' => $postalCode, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/postal_code/list', name: 'postal_code_list')]
    public function list(PostalCodeRepository $postalCodeRepository): Response
    {

        $listGrid = ['title' => 'PostalCode',
                     'link_id' => 'id-postalCode',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'firstName',
                                    'action' => 'display',],
                     ],
                     'createButtonConfig' => ['link_id' => ' id-create-postalCode',
                                              'function' => 'postalCode',
                                              'anchorText' => 'create PostalCode']];

        $postalCodes = $postalCodeRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $postalCodes, 'listGrid' => $listGrid]
        );
    }
}