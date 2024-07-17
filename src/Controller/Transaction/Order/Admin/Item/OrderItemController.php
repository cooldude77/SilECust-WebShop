<?php
// src/Controller/OrderItemController.php
namespace App\Controller\Transaction\Order\Admin\Item;

// ...
use App\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use App\Form\Transaction\Order\Item\OrderItemCreateForm;
use App\Repository\OrderItemRepository;
use App\Service\Transaction\Order\Item\Mapper\OrderItemDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{
    #[Route('/order/{id}/item/create', name: 'order_item_create')]
    public function create(int $id, EntityManagerInterface $entityManager,
        OrderItemDTOMapper $orderItemMapper, Request $request
    ): Response {
        $orderItemDTO = new OrderItemDTO();
        $orderItemDTO->orderHeaderId = $id;

        $form = $this->createForm(OrderItemCreateForm::class, $orderItemDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderItem = $orderItemMapper->mapToEntityForCreate($form->getData());

            $entityManager->persist($orderItem);
            $entityManager->flush();

            $this->addFlash(
                'success', "Order Item created successfully"
            );

            $id = $orderItem->getId();
            $this->addFlash(
                'success', "Order Item created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order Item created successfully"]
                ), 200
            );

        }

        return $this->render('transaction/order/item/order_item_create.html.twig', ['form' => $form]
        );
    }

    #[\Symfony\Component\Routing\Attribute\Route('/order/item/{id}/edit', name: 'order_item_edit')]
    public function edit(int $id, OrderItemDTOMapper $mapper,
        EntityManagerInterface $entityManager, OrderItemRepository $orderItemRepository,
        Request $request
    ): Response {
        $orderItemDTO = new OrderItemDTO();

        $orderItem = $orderItemRepository->find($id);

        $form = $this->createForm(OrderItemEditForm::class, $orderItemDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderItem = $mapper->mapDtoToEntityForEdit(
                $form->getData(), $orderItem
            );

            $entityManager->persist($orderItem);
            $entityManager->flush();

            $this->addFlash(
                'success', "Order Item updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order Item updated successfully"]
                ), 200
            );
        }

        return $this->render('transaction/order/item/order_item_edit.html.twig', ['form' => $form]);


    }

    #[Route('/order/item/{id}/display', name: 'order_item_display')]
    public function display(OrderItemRepository $OrderItemRepository, int $id): Response
    {
        $OrderItem = $OrderItemRepository->find($id);
        if (!$OrderItem) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $displayParams = ['title' => 'Price',
                          'link_id' => 'id-price',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'id',
                                        'propertyName' => 'id',],
                          ]];

        return $this->render(
            'transaction/order/item/order_item_display.html.twig',
            ['entity' => $OrderItem, 'params' => $displayParams]
        );
    }

    #[\Symfony\Component\Routing\Attribute\Route('/order/item/list', name: 'order_item_list')]
    public function list(OrderItemRepository $orderRepository, PaginatorInterface $paginator,
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