<?php
// src/Controller/OrderHeaderController.php
namespace App\Controller\Transaction\Order\Admin\Header;

// ...
use App\Entity\OrderHeader;
use App\Form\Common\Order\Header\OrderHeaderCreateForm;
use App\Form\Transaction\Admin\Order\Header\OrderHeaderDTO;
use App\Repository\OrderHeaderRepository;
use App\Service\Module\WebShop\External\Order\Mapper\Components\OrderHeaderMapper;
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
        OrderHeaderMapper $orderHeaderMapper,
    Request
    $request
    ): Response {
        $orderHeaderDTO = new OrderHeaderDTO();

        $form = $this->createForm(OrderHeaderCreateForm::class, $orderHeaderDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // perform some action...
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('common/order/header/success_create.html.twig');
        }

return $this->render('transaction/order/order_create.html.twig', ['form' => $form]);
}


#[
\Symfony\Component\Routing\Attribute\Route('/order/list', name: 'order_list')]
    public function list(OrderHeaderRepository $orderRepository, PaginatorInterface $paginator,
        Request $request
    ):
    Response {

    $listGrid = ['title' => 'Order',
                 'link_id' => 'id-order',
                 'columns' => [['label' => 'Name',
                                'propertyName' => 'name',
                                'action' => 'display',],
                               ['label' => 'Description',
                                'propertyName' => 'dateTimeOfOrder'],],
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