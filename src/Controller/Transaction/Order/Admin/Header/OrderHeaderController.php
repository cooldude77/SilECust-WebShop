<?php
// src/Controller/OrderHeaderController.php
namespace App\Controller\Transaction\Order\Admin\Header;

// ...
use App\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use App\Form\Transaction\Order\Header\OrderHeaderCreateForm;
use App\Form\Transaction\Order\Header\OrderHeaderEditForm;
use App\Repository\OrderHeaderRepository;
use App\Service\Transaction\Order\Mapper\Components\OrderHeaderDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderHeaderController extends AbstractController
{
    #[Route('/order/create', name: 'order_create')]
    public function createOrderHeader(EntityManagerInterface $entityManager,
        OrderHeaderDTOMapper $orderHeaderMapper, Request $request
    ): Response {
        $orderHeaderDTO = new OrderHeaderDTO();

        $form = $this->createForm(OrderHeaderCreateForm::class, $orderHeaderDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderHeader = $orderHeaderMapper->mapToEntityForCreate($form->getData());

            $entityManager->persist($orderHeader);
            $entityManager->flush();

            $this->addFlash(
                'success', "Order created successfully"
            );

            $id = $orderHeader->getId();
            $this->addFlash(
                'success', "Order created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order created successfully"]
                ), 200
            );

        }

        return $this->render('transaction/order/order_create.html.twig', ['form' => $form]);
    }

    #[\Symfony\Component\Routing\Attribute\Route('/order/{id}/edit', name: 'order_edit')]
    public function edit(int $id, OrderHeaderDTOMapper $mapper,
        EntityManagerInterface $entityManager, OrderHeaderRepository $orderHeaderRepository,
        Request $request
    ): Response {
        $orderHeaderDTO = new OrderHeaderDTO();

        $orderHeader = $orderHeaderRepository->find($id);

        $form = $this->createForm(OrderHeaderEditForm::class, $orderHeaderDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderHeader = $mapper->mapDtoToEntityForEdit(
                $form->getData(), $orderHeader
            );

            $entityManager->persist($orderHeader);
            $entityManager->flush();

            $this->addFlash(
                'success', "Order updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order updated successfully"]
                ), 200
            );
        }

        return $this->render('transaction/order/order_edit.html.twig', ['form' => $form]);


    }

    #[Route('/order/{id}/display', name: 'order_display')]
    public function display(OrderHeaderRepository $OrderHeaderRepository, int $id): Response
    {
        $OrderHeader = $OrderHeaderRepository->find($id);
        if (!$OrderHeader) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Price',
                          'link_id' => 'id-price',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'id',
                                        'propertyName' => 'id',],
                          ]];

        return $this->render(
            'transaction/order/order_display.html.twig',
            ['entity' => $OrderHeader, 'params' => $displayParams]
        );
    }

    #[\Symfony\Component\Routing\Attribute\Route('/order/list', name: 'order_list')]
    public function list(OrderHeaderRepository $orderRepository, PaginatorInterface $paginator,
        Request $request
    ): Response {

        $listGrid = ['title' => 'Order',
                     'link_id' => 'id-order',
                     'columns' => [['label' => 'Id',
                                    'propertyName' => 'id',
                                    'action' => 'display',],],
                     'createButtonConfig' => ['link_id' => ' id-create-order',
                                              'function' => 'order',
                                              'anchorText' => 'Create Order']];

        $query = $orderRepository->getQueryForSelect();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */ $request->query->getInt('page', 1),
            /*page number*/ 1 /*limit per page*/
        );

        return $this->render(
            'admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid]
        );
    }
}