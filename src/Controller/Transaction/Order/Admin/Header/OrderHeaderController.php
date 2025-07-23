<?php
// src/Controller/OrderHeaderController.php
namespace Silecust\WebShop\Controller\Transaction\Order\Admin\Header;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Event\Transaction\Order\Header\BeforeOrderHeaderChangedEvent;
use Silecust\WebShop\Exception\Transaction\Order\Admin\Header\OpenOrderEditedInAdminPanel;
use Silecust\WebShop\Exception\Transaction\Order\Admin\Header\OrderHeaderNotFound;
use Silecust\WebShop\Form\Transaction\Order\Header\DTO\OrderHeaderDTO;
use Silecust\WebShop\Form\Transaction\Order\Header\OrderHeaderCreateForm;
use Silecust\WebShop\Form\Transaction\Order\Header\OrderHeaderEditForm;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Security\Voter\Order\Header\CustomerVoter;
use Silecust\WebShop\Service\Transaction\Order\Admin\Header\OrderStatusValidator;
use Silecust\WebShop\Service\Transaction\Order\Mapper\Components\OrderHeaderDTOMapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderHeaderController extends EnhancedAbstractController
{
    // Right now no creation from panel
    // #[Route('/admin/order/create', name: 'sc_admin_order_create')]
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
     * @param string $generatedId
     * @param OrderHeaderDTOMapper $mapper
     * @param OrderStatusValidator $orderStatusValidator
     * @param EntityManagerInterface $entityManager
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    #[Route('/admin/order/{generatedId}/edit', name: 'sc_admin_order_edit')]
    public function edit(
        string                   $generatedId,
        OrderHeaderDTOMapper     $mapper,
        OrderStatusValidator     $orderStatusValidator,
        EntityManagerInterface   $entityManager,
        OrderHeaderRepository    $orderHeaderRepository,
        EventDispatcherInterface $eventDispatcher,
        Request                  $request
    ): Response
    {

        $this->setContentHeading($request, "Edit Order $generatedId");
        try {
            /** @var \Silecust\WebShop\Entity\OrderHeader $orderHeader */
            $orderHeader = $orderHeaderRepository->findOneBy(['generatedId' => $generatedId]);

            $this->denyAccessUnlessGranted(CustomerVoter::EDIT, $orderHeader);


            if ($orderHeader == null)
                throw  new OrderHeaderNotFound(['generatedId' => $generatedId]);


            $orderStatusValidator->checkOrderStatus($orderHeader, 'edit');

            $orderHeaderDTO = $mapper->mapEntityToDtoForEdit($orderHeader);

            $form = $this->createForm(OrderHeaderEditForm::class, $orderHeaderDTO, ['statusType' => $orderHeader->getOrderStatusType()]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $entityManager->beginTransaction();


                    /** @var OrderHeaderDTO $orderHeaderDTO */
                    $orderHeaderDTO = $form->getData();

                    $event = new BeforeOrderHeaderChangedEvent();
                    $event->setOrderHeader($orderHeader);
                    $event->setRequestData(json_decode(json_encode($orderHeaderDTO), true));

                    $eventDispatcher->dispatch($event, BeforeOrderHeaderChangedEvent::EVENT_NAME);

                    $mapper->mapDtoToEntityForEdit($orderHeaderDTO);

                    $entityManager->flush();
                    $entityManager->commit();

                } catch (Exception $exception) {
                    $entityManager->rollback();
                    throw $exception;
                }
                $this->addFlash('success', "Order updated successfully");

                return new Response(
                    serialize(
                        ['id' => $orderHeader->getId(), 'message' => "Order updated successfully"]
                    ), 200
                );
            }

            return $this->render('@SilecustWebShop/transaction/admin/order/header/order_edit.html.twig', ['form' => $form]);
        } catch (OpenOrderEditedInAdminPanel $e) {
            return new Response($e->getMessage(), 409);
        } catch (OrderHeaderNotFound $e) {
            return new Response($e->getMessage(), 404);
        }

    }

    #[Route('/admin/order/{generatedId}/display', name: 'sc_admin_order_display')]
    public function display(
        string                   $generatedId,
        EventDispatcherInterface $eventDispatcher,
        OrderHeaderRepository    $orderHeaderRepository,
        OrderStatusValidator     $orderStatusValidator,
        Request                  $request): Response
    {

        try {
            $orderHeader = $orderHeaderRepository->findOneBy(['generatedId' => $generatedId]);

            if ($orderHeader == null)
                throw  new OrderHeaderNotFound(['generatedId' => $generatedId]);
            $orderStatusValidator->checkOrderStatus($orderHeader, 'edit');

            $this->denyAccessUnlessGranted(CustomerVoter::DISPLAY, $orderHeader);
            // NOTE: This grid can be called as a subsection to main screen
            $displayParamsEvent = $eventDispatcher->dispatch(
                new DisplayParamPropertyEvent($request), DisplayParamPropertyEvent::EVENT_NAME);

            return $this->render(
                '@SilecustWebShop/transaction/admin/order/header/order_display.html.twig',
                [
                    'entity' => $orderHeader,
                    'params' => $displayParamsEvent->getDisplayParamProperties(),
                    'request' => $request
                ]
            );
        } catch (OpenOrderEditedInAdminPanel $e) {
            return new Response($e->getMessage(), 409);
        } catch (OrderHeaderNotFound $e) {
            return new Response($e->getMessage(), 404);
        }
    }

    #[Route('/admin/order/list', name: 'sc_admin_order_list')]
    public function list(
        PaginatorInterface       $paginator,
        EventDispatcherInterface $eventDispatcher,
        Request                  $request
    ): Response
    {
        /** @var GridPropertyEvent $listEvent */
        $listEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request),
            GridPropertyEvent::EVENT_NAME
        );

        $listGrid = $listEvent->getListGridProperties();

        $listQueryEvent = $eventDispatcher->dispatch(new ListQueryEvent($request), ListQueryEvent::BEFORE_LIST_QUERY);

        $query = $listQueryEvent->getQuery();

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