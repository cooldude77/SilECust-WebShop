<?php
// src/Controller/OrderItemController.php
namespace App\Controller\Transaction\Order\Admin\Item;

// ...
use App\Event\Component\UI\Panel\Display\DisplayParametersEvent;
use App\Event\Component\UI\Panel\List\GridPropertyEvent;
use App\Event\Transaction\Order\Item\OrderItemAddEvent;
use App\Event\Transaction\Order\Item\OrderItemEditEvent;
use App\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use App\Form\Transaction\Order\Item\OrderItemCreateForm;
use App\Form\Transaction\Order\Item\OrderItemEditForm;
use App\Repository\OrderItemRepository;
use App\Service\Transaction\Order\Item\Mapper\OrderItemDTOMapper;
use App\Service\Transaction\Order\Item\Mapper\OrderItemPaymentPriceMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
 use App\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderItemController extends EnhancedAbstractController
{
    #[Route('/order/{id}/item/create', name: 'order_item_create')]
    public function create(int                         $id, EntityManagerInterface $entityManager,
                           OrderItemDTOMapper          $orderItemMapper,
                           OrderItemPaymentPriceMapper $orderItemPaymentPriceMapper,
                           EventDispatcherInterface    $eventDispatcher,
                           Request                     $request
    ): Response
    {
        $orderItemDTO = new OrderItemDTO();
        $orderItemDTO->orderHeaderId = $id;

        $form = $this->createForm(OrderItemCreateForm::class, $orderItemDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderItem = $orderItemMapper->mapToEntityForCreate($form->getData());
            $orderItemPaymentPrice = $orderItemPaymentPriceMapper->mapToEntityForCreate($orderItem);

            $entityManager->persist($orderItem);
            $entityManager->persist($orderItemPaymentPrice);

            $eventDispatcher->dispatch(new OrderItemAddEvent($orderItem),OrderItemAddEvent::ORDER_ITEM_ADDED);

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

    #[Route('/order/item/{id}/edit', name: 'order_item_edit')]
    public function edit(int                         $id,
                         OrderItemDTOMapper          $mapper,
                         EntityManagerInterface      $entityManager,
                         OrderItemRepository         $orderItemRepository,
                         OrderItemPaymentPriceMapper $orderItemPaymentPriceMapper,
                         EventDispatcherInterface    $eventDispatcher,
                         Request                     $request
    ): Response
    {

        $orderItem = $orderItemRepository->find($id);

        $orderItemDTO = $mapper->mapFromEntityToDtoForEdit($orderItem);

        $form = $this->createForm(OrderItemEditForm::class, $orderItemDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderItem = $mapper->mapDtoToEntityForEdit($form->getData());

            $orderItemPaymentPrice = $orderItemPaymentPriceMapper->mapToEntityForEdit($orderItem);

            $entityManager->persist($orderItem);
            $entityManager->persist($orderItemPaymentPrice);

            $entityManager->flush();
            $eventDispatcher->dispatch(new OrderItemEditEvent($orderItem),OrderItemEditEvent::ORDER_ITEM_EDITED);

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
    public function display(OrderItemRepository $OrderItemRepository, int $id, EventDispatcherInterface $eventDispatcher, Request $request): Response
    {
        $OrderItem = $OrderItemRepository->find($id);
        if (!$OrderItem) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }
        /** @var DisplayParametersEvent $event */
        $event = $eventDispatcher->dispatch(new DisplayParametersEvent($request, ['id' => $id]),
            DisplayParametersEvent::GET_DISPLAY_PROPERTY_EVENT
        );

        $displayParams = $event->getParameterList();

        return $this->render(
            'transaction/order/item/order_item_display.html.twig',
            ['entity' => $OrderItem, 'params' => $displayParams]
        );
    }

    #[Route('/order/{id}/item/list', name: 'order_item_list')]
    public function list(int                      $id,
                         EventDispatcherInterface $eventDispatcher,
                         PaginatorInterface       $paginator,
                         Request                  $request,
                         OrderItemRepository      $orderItemRepository
    ): Response
    {


        /** @var GridPropertyEvent $listEvent */
        $listEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request, ['id' => $id]),
            GridPropertyEvent::LIST_GRID_PROPERTY_FOR_ORDERS
        );

        $listGrid = $listEvent->getListGridProperties();


        $query = $orderItemRepository->getQueryForSelect();

        // $query = $orderRepository->getQueryForSelect();

        // todo : to bring price ( calculated field on the list)
        $pagination = $paginator->paginate(
            $query, /* query NOT result */ $request->query->getInt('page', 1),
            /*page number*/ 10 /*limit per page*/
        );

        return $this->render(
            'admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }
}