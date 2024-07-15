<?php
// src/Controller/OrderItemController.php
namespace App\Controller\Transaction\Order\Admin\Item;

// ...
use App\Entity\OrderItem;
use App\Form\Common\Order\Item\OrderItemCreateForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{
    #[Route('/order/header/{id}/item/create', name: 'order_item_create')]
    public function createOrderItem($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $orderItem = new OrderItem();

        $form = $this->createForm(OrderItemCreateForm::class, $orderItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // perform some action...
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('common/order/item/success_create.html.twig');
        }
        return $this->render('common/order/item/create.html.twig', ['form' => $form]);
    }


    #[Route('/orderItem/edit/{id}', name: 'orderItem_edit')]

    #[\Symfony\Component\Routing\Attribute\Route('/order/list', name: 'product_list')]
    public function list(ProductRepository $productRepository,PaginatorInterface $paginator,
        Request $request):
    Response
    {

        $listGrid = ['title' => 'Order',
                     'link_id' => 'id-order',
                     'columns' => [['label' => 'Id',
                                    'propertyName' => 'orderGuid',
                                    'action' => 'display',],
                                   ['label' => 'Date Created', 'propertyName' => 'dateP'],],
                     'createButtonConfig' => ['link_id' => ' id-create-order',
                                              'function' => 'order',
                                              'anchorText' => 'Create Order']];

        $query = $productRepository->getQueryForSelect();

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