<?php
// src/Controller/OrderHeaderController.php
namespace App\Controller\Common\Order\Admin\Header;

// ...
use App\Entity\OrderHeader;
use App\Form\Common\Order\Header\OrderHeaderCreateForm;
use App\Repository\OrderHeaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderHeaderController extends AbstractController
{
    #[Route('/order/create', name: 'order_create')]
    public function createOrderHeader(EntityManagerInterface $entityManager, Request $request): Response
    {
        $type = new OrderHeader();

        $form = $this->createForm(OrderHeaderCreateForm::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // perform some action...
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('common/order/header/success_create.html.twig');
        }
        return $this->render('transaction/order/order_create.html.twig', ['form' => $form]);
    }


    #[Route('/order/header/edit/{id}', name: 'order_header_edit')]
    public function update(OrderHeaderRepository $orderHeaderRepository, int $id): Response
    {
        $orderHeader = $orderHeaderRepository->find($id);


        if (!$orderHeader) {
            throw $this->createNotFoundException(
                'No orderHeader found for id ' . $id
            );
        }

        $orderHeader->setOrderHeaderDescription('New .... ');
        $orderHeaderRepository->getEntityManager()->flush($orderHeader);

        return new Response('Check out this updated orderHeader: ' . $orderHeader->getOrderHeaderDescription());

        // or render a template
        // in the template, print things with {{ orderHeader.name }}
        // return $this->render('orderHeader/show.html.twig', ['orderHeader' => $orderHeader]);
    }
}