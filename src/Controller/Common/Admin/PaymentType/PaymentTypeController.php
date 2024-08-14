<?php
// src/Controller/PaymentTypeController.php
namespace App\Controller\Common\Admin\PaymentType;

// ...
use App\Form\Common\Admin\PaymentType\DTO\PaymentTypeDTO;
use App\Form\Common\Admin\PaymentType\PaymentTypeCreateForm;
use App\Form\Common\Admin\PaymentType\PaymentTypeEditForm;
use App\Repository\PaymentTypeRepository;
use App\Service\Common\PaymentType\Mapper\PaymentTypeDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentTypeController extends AbstractController
{

    #[\Symfony\Component\Routing\Attribute\Route('/payment_type/create', 'payment_type_create')]
    public function create(PaymentTypeDTOMapper $paymentTypeDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $paymentTypeDTO = new PaymentTypeDTO();
        $form = $this->createForm(
            PaymentTypeCreateForm::class, $paymentTypeDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $paymentTypeEntity = $paymentTypeDTOMapper->mapToEntityForCreate($form);


            // perform some action...
            $entityManager->persist($paymentTypeEntity);
            $entityManager->flush();


            $id = $paymentTypeEntity->getId();

            $this->addFlash(
                'success', "PaymentType created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "PaymentType created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('common/payment_type/payment_type_create.html.twig', ['form' => $form]);
    }


    #[Route('/payment_type/{id}/edit', name: 'payment_type_edit')]
    public function edit(EntityManagerInterface $entityManager,
        PaymentTypeRepository $paymentTypeRepository, PaymentTypeDTOMapper $paymentTypeDTOMapper, Request $request,
        int $id
    ): Response {
        $paymentType = $paymentTypeRepository->find($id);


        if (!$paymentType) {
            throw $this->createNotFoundException('No Payment Type found for id ' . $id);
        }

        $paymentTypeDTO = new PaymentTypeDTO();
        $paymentTypeDTO->id = $id;

        $form = $this->createForm(PaymentTypeEditForm::class, $paymentTypeDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $paymentType = $paymentTypeDTOMapper->mapToEntityForEdit($form, $paymentType);
            // perform some action...
            $entityManager->persist($paymentType);
            $entityManager->flush();

            $id = $paymentType->getId();

            $this->addFlash(
                'success', "Payment Type updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Payment Type updated successfully"]
                ), 200
            );
        }

        return $this->render('common/payment_type/payment_type_edit.html.twig', ['form' => $form]);
    }

    #[Route('/payment_type/{id}/display', name: 'payment_type_display')]
    public function display(PaymentTypeRepository $paymentTypeRepository, int $id): Response
    {
        $paymentType = $paymentTypeRepository->find($id);
        if (!$paymentType) {
            throw $this->createNotFoundException('No paymentType found for id ' . $id);
        }

        $displayParams = ['title' => 'PaymentType',
                          'link_id' => 'id-payment-type',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Name',
                                        'propertyName' => 'name',
                                        'link_id' => 'id-display-payment-type'],
                                       ['label' => 'Description',
                                        'propertyName' => 'description'],]];

        return $this->render(
            'common/payment_type/payment_type_display.html.twig',
            ['entity' => $paymentType, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/payment_type/list', name: 'payment_type_list')]
    public function list(PaymentTypeRepository $paymentTypeRepository,PaginatorInterface $paginator,
    Request $request):
    Response
    {

        $listGrid = ['title' => 'PaymentType',
                     'link_id' => 'id-paymentType',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description', 'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => ' id-create-paymentType',
                                              'function' => 'paymentType',
                                              'anchorText' => 'Create PaymentType']];

        $query = $paymentTypeRepository->getQueryForSelect();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            1 /*limit per page*/
        );

        return $this->render(
            'admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid]
        );
    }
}