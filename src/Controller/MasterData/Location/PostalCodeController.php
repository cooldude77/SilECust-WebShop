<?php

namespace Silecust\WebShop\Controller\MasterData\Location;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\DTO\PostalCodeDTO;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\Attribute\PostalCode\PostalCodeEditForm;
use Silecust\WebShop\Repository\CityRepository;
use Silecust\WebShop\Repository\PostalCodeRepository;
use Silecust\WebShop\Service\Location\Mapper\PostalCode\PostalCodeDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PostalCodeController extends EnhancedAbstractController
{
    #[Route('/admin/postal_code/city/{id}/create', 'sc_admin_postal_code_create')]
    public function create(
        int                    $id,
        CityRepository         $cityRepository,
        PostalCodeDTOMapper    $postalCodeDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $city = $cityRepository->find($id);

        if (!$city) {
            throw $this->createNotFoundException('No City found for id ' . $id);
        }

        $postalCodeDTO = new PostalCodeDTO();
        $postalCodeDTO->cityId = $city->getId();

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
            '@SilecustWebShop/location_data/admin/postal_code/postal_code_create.html.twig', ['form' => $form]
        );
    }


    #[Route('/admin/postal_code/{id}/edit', name: 'sc_admin_postal_code_edit')]
    public function edit(
        int                    $id,
        PostalCodeRepository   $postalCodeRepository,
        EntityManagerInterface $entityManager,
        PostalCodeDTOMapper    $postalCodeDTOMapper,
        Request                $request,
    ): Response
    {
        $postalCode = $postalCodeRepository->find($id);

        if (!$postalCode) {
            throw $this->createNotFoundException('No PostalCode found for id ' . $id);
        }

        $postalCodeDTO = $postalCodeDTOMapper->mapToDTOForEdit($postalCode);

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

        return $this->render('@SilecustWebShop/location_data/admin/postal_code/postal_code_edit.html.twig', ['form' =>
                $form]
        );
    }

    #[Route('/admin/postal_code/{id}/display', name: 'sc_admin_postal_code_display')]
    public function display(
        int                  $id,
        PostalCodeRepository $postalCodeRepository,
        Request              $request,
    ): Response
    {
        $postalCode = $postalCodeRepository->find($id);

        if (!$postalCode) {
            throw $this->createNotFoundException('No PostalCode found for id ' . $id);
        }


        $displayParams = ['title' => 'PostalCode',
            'link_id' => 'id-postalCode',
            'editButtonLinkText' => 'Edit',
            'fields' => [['label' => 'Postal Code',
                'propertyName' => 'code',
                'link_id' => 'id-display-postalCode'], ['label' => 'Name',
                'propertyName' => 'name'
                ,],]];

        return $this->render(
            '@SilecustWebShop/location_data/admin/postal_code/postal_code_display.html.twig',
            ['request' => $request, 'entity' => $postalCode, 'params' => $displayParams]
        );

    }

    #[Route('/admin/postal_code/city/{id}/list', name: 'sc_postal_code_list')]
    public function list(
        int                  $id,
        CityRepository       $cityRepository,
        PostalCodeRepository $postalCodeRepository,
        Request              $request,
        PaginatorInterface   $paginator): Response
    {
        $this->setContentHeading($request, 'Postal Code');

        $city = $cityRepository->find($id);

        if (!$city) {
            throw $this->createNotFoundException('No City found for id ' . $id);
        }

        $listGrid = ['title' => 'PostalCode',
            'link_id' => 'id-postalCode',
            'function' => 'postal_code',
            'edit_link_allowed' => true,
            'id' => $city->getId(),
            'columns' => [
                ['label' => 'Postal Code',
                    'propertyName' => 'code',
                    'action' => 'display',],
                ['label' => 'Name',
                    'propertyName' => 'name'
                    ,],
            ],
            'createButtonConfig' => ['link_id' => ' id-create-postalCode',
                'id' => $city->getId(),

                'function' => 'postal_code',
                'anchorText' => 'create PostalCode']];


        $query = $postalCodeRepository->getQueryForSelect($city);

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