<?php

namespace App\Controller\Module\WebShop\Admin\Transaction\Order;

use App\Controller\Transaction\Order\OrderCreateForm;
use App\Controller\Transaction\Order\OrderDTO;
use App\Controller\Transaction\Order\OrderDTOMapper;
use App\Controller\Transaction\Order\OrderEditForm;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderMainController extends AbstractController
{
{

    #[\Symfony\Component\Routing\Attribute\Route('/order/create', 'order_create')]
    public function create(OrderDTOMapper $orderDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $orderDTO = new OrderDTO();
        $form = $this->createForm(
            OrderCreateForm::class, $orderDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $orderEntity = $orderDTOMapper->mapToEntityForCreate($form);


            // perform some action...
            $entityManager->persist($orderEntity);
            $entityManager->flush();


            $id = $orderEntity->getId();

            $this->addFlash(
                'success', "Order created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('master_data/order/order_create.html.twig', ['form' => $form]);
    }


    #[Route('/order/{id}/edit', name: 'order_edit')]
    public function edit(EntityManagerInterface $entityManager,
        OrderRepository $orderRepository, OrderDTOMapper $orderDTOMapper, Request $request,
        int $id
    ): Response {
        $order = $orderRepository->find($id);


        if (!$order) {
            throw $this->createNotFoundException('No order found for id ' . $id);
        }

        $orderDTO = new OrderDTO();
        $orderDTO->id = $id;

        $form = $this->createForm(OrderEditForm::class, $orderDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order = $orderDTOMapper->mapToEntityForEdit($form, $order);
            // perform some action...
            $entityManager->persist($order);
            $entityManager->flush();

            $id = $order->getId();

            $this->addFlash(
                'success', "Order updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order updated successfully"]
                ), 200
            );
        }

        return $this->render('master_data/order/order_edit.html.twig', ['form' => $form]);
    }

    #[Route('/order/{id}/display', name: 'order_display')]
    public function display(OrderRepository $orderRepository, int $id): Response
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            throw $this->createNotFoundException('No order found for id ' . $id);
        }

        $displayParams = ['title' => 'Order',
                          'link_id' => 'id-order',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'Name',
                                        'propertyName' => 'name',
                                        'link_id' => 'id-display-order'],
                                       ['label' => 'Description',
                                        'propertyName' => 'description'],]];

        return $this->render(
            'master_data/order/order_display.html.twig',
            ['entity' => $order, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/order/list', name: 'order_list')]
    public function list(OrderRepository $orderRepository, PaginatorInterface $paginator,
        Request $request
    ):
    Response {

        $listGrid = ['title' => 'Order',
                     'link_id' => 'id-order',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'name',
                                    'action' => 'display',],
                                   ['label' => 'Description', 'propertyName' => 'description'],],
                     'createButtonConfig' => ['link_id' => ' id-create-order',
                                              'function' => 'order',
                                              'anchorText' => 'Create Order']];

        $query = $orderRepository->getQueryForSelect();

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