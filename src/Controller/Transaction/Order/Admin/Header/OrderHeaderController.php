<?php
// src/Controller/OrderHeaderController.php
namespace Silecust\WebShop\Controller\Transaction\Order\Admin\Header;

// ...
use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Event\Transaction\Order\Header\OrderHeaderChangedEvent;
use Silecust\WebShop\Exception\Transaction\Order\Admin\Header\OpenOrderEditedInAdminPanel;
use Silecust\WebShop\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use Silecust\WebShop\Form\Transaction\Order\Header\OrderHeaderCreateForm;
use Silecust\WebShop\Form\Transaction\Order\Header\OrderHeaderEditForm;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Service\Transaction\Order\Admin\Header\OrderStatusValidator;
use Silecust\WebShop\Service\Transaction\Order\Mapper\Components\OrderHeaderDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderHeaderController extends EnhancedAbstractController
{
    // Right now no creation from panel
    // #[Route('/admin/order/create', name: 'sc_admin_route_order_create')]
    public function createOrderHeader(
        EntityManagerInterface $entityManager,
        OrderHeaderDTOMapper   $orderHeaderMapper,
        Request                $request
    ): Response
    {
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

        return $this->render('@SilecustWebShop/transaction/admin/order/header/order_create.html.twig', ['form' => $form]);
    }

    /**
     * @throws \HttpException
     */
    #[Route('/admin/order/{generatedId}/edit', name: 'sc_admin_route_order_edit')]
    public function edit(OrderHeader              $orderHeader,
                         OrderHeaderDTOMapper     $mapper,
                         OrderStatusValidator     $orderStatusValidator,
                         EntityManagerInterface   $entityManager,
                         OrderHeaderRepository    $orderHeaderRepository,
                         EventDispatcherInterface $eventDispatcher,
                         Request                  $request
    ): Response
    {


        try {
            $orderStatusValidator->checkOrderStatus($orderHeader, 'edit');

            $orderHeaderDTO = new OrderHeaderDTO();
            $orderHeaderDTO->id = $orderHeader->getId();

            $form = $this->createForm(OrderHeaderEditForm::class, $orderHeaderDTO);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $orderHeader = $mapper->mapDtoToEntityForEdit(
                    $form->getData()
                );

                $entityManager->persist($orderHeader);
                $entityManager->flush();

                $eventDispatcher->dispatch(new OrderHeaderChangedEvent($orderHeader), OrderHeaderChangedEvent::ORDER_HEADER_CHANGED);

                $this->addFlash(
                    'success', "Order updated successfully"
                );

                return new Response(
                    serialize(
                        ['id' => $orderHeader->getId(), 'message' => "Order updated successfully"]
                    ), 200
                );
            }

            return $this->render('@SilecustWebShop/transaction/admin/order/header/order_edit.html.twig', ['form' => $form]);
        } catch (OpenOrderEditedInAdminPanel $e) {
            return new Response($e->getMessage(), 409);
        }

    }

    #[Route('/admin/order/{generatedId}/display', name: 'sc_admin_route_order_display')]
    public function display(OrderHeader $orderHeader, OrderStatusValidator $orderStatusValidator, Request $request): Response
    {
        try {
            $orderStatusValidator->checkOrderStatus($orderHeader, 'edit');

            $displayParams = ['title' => 'Price',
                'link_id' => 'id-price',
                'editButtonLinkText' => 'Edit',
                'fields' => [['label' => 'id',
                    'propertyName' => 'id',],]];

            return $this->render(
                'transaction/admin/order/header/order_display.html.twig',
                ['entity' => $orderHeader, 'params' => $displayParams, 'request' => $request]
            );
        } catch (OpenOrderEditedInAdminPanel $e) {
            return new Response($e->getMessage(), 409);
        }

    }

    #[Route('/admin/order/list', name: 'sc_admin_route_order_list')]
    public function list(OrderHeaderRepository    $orderRepository,
                         PaginatorInterface       $paginator,
                         EventDispatcherInterface $eventDispatcher,
                         Request                  $request
    ): Response
    {

        /** @var GridPropertyEvent $listEvent */
        $listEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request),
            GridPropertyEvent::LIST_GRID_PROPERTY_FOR_ORDERS
        );

        $listGrid = $listEvent->getListGridProperties();

        $listQueryEvent = $eventDispatcher->dispatch(new ListQueryEvent($request), ListQueryEvent::BEFORE_LIST_QUERY);

        $query = $listQueryEvent->getQuery();

        // $query = $orderRepository->getQueryForSelect();

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