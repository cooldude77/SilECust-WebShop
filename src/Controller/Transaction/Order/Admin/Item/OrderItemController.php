<?php
// src/Controller/OrderItemController.php
namespace Silecust\WebShop\Controller\Transaction\Order\Admin\Item;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\TopLevel\DisplayParametersEvent;
use Silecust\WebShop\Event\Transaction\Order\Item\BeforeOrderItemChangedEvent;
use Silecust\WebShop\Event\Transaction\Order\Item\OrderItemAddEvent;
use Silecust\WebShop\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use Silecust\WebShop\Form\Transaction\Order\Item\OrderItemCreateForm;
use Silecust\WebShop\Form\Transaction\Order\Item\OrderItemEditForm;
use Silecust\WebShop\Repository\OrderItemRepository;
use Silecust\WebShop\Service\Transaction\Order\Item\Mapper\OrderItemDTOMapper;
use Silecust\WebShop\Service\Transaction\Order\Item\Mapper\OrderItemPaymentPriceMapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderItemController extends EnhancedAbstractController
{
    #[Route('/admin/order/{id}/item/create', name: 'sc_admin_order_item_create')]
    public function create(
        int                         $id,
        EntityManagerInterface      $entityManager,
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

            $eventDispatcher->dispatch(new OrderItemAddEvent($orderItem), OrderItemAddEvent::EVENT_NAME);

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

        return $this->render('@SilecustWebShop/transaction/order/item/order_item_create.html.twig', ['form' => $form]
        );
    }

    /**
     * @throws \Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound
     * @throws \Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductTaxNotFound
     */
    #[Route('/admin/order/item/{id}/edit', name: 'sc_admin_order_item_edit')]
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

            try {
                $entityManager->beginTransaction();

                /** @var OrderItemDTO $orderItemDTOSubmit */
                $orderItemDTOSubmit = $form->getData();

                $eventDispatcher->dispatch(new BeforeOrderItemChangedEvent($orderItem,
                    json_decode(json_encode($orderItemDTOSubmit), true)),
                    BeforeOrderItemChangedEvent::EVENT_NAME);

                $mapper->mapDtoToEntityForEdit($orderItemDTO);

                $orderItemPaymentPriceMapper->mapToEntityForEdit($orderItem);

                $entityManager->flush();
                $entityManager->commit();

            } catch (Exception $exception) {
                $entityManager->rollback();
                throw $exception;
            }

            $this->addFlash(
                'success', "Order Item updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Order Item updated successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/transaction/admin/order/item/order_item_edit.html.twig', ['form' => $form]);


    }

    #[Route('/admin/order/item/{id}/display', name: 'sc_admin_order_item_display')]
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
            '@SilecustWebShop/transaction/admin/order/item/order_item_display.html.twig',
            ['entity' => $OrderItem, 'request' => $request, 'params' => $displayParams]
        );
    }

    #[Route('/admin/order/{id}/item/list', name: 'sc_admin_order_item_list')]
    public function list(int                      $id,
                         EventDispatcherInterface $eventDispatcher,
                         PaginatorInterface       $paginator,
                         Request                  $request,
                         OrderItemRepository      $orderItemRepository
    ): Response
    {


        /** @var GridPropertyEvent $listEvent */
        $listEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request, ['id' => $id]),
            GridPropertyEvent::EVENT_NAME
        );

        $listGrid = $listEvent->getListGridProperties();

        $query = $orderItemRepository->getQueryForSelect();

        // todo : to bring price ( calculated field on the list)
        $pagination = $paginator->paginate(
            $query, /* query NOT result */ $request->query->getInt('page', 1),
            /*page number*/ 10 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGrid, 'request' => $request]
        );
    }
}