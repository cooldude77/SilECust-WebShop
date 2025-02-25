<?php
// src/Controller/PaymentTypeController.php
namespace Silecust\WebShop\Controller\Module\WebShop\External\Payment;

// ...
use Silecust\WebShop\Form\Module\WebShop\External\PaymentType\PaymentTypeChoiceForm;
use Silecust\WebShop\Service\Module\WebShop\External\Payment\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentTypeController extends EnhancedAbstractController
{

    #[Route('/web_shop/payment', 'web_shop_payment')]
    public function payment(EntityManagerInterface $entityManager, PaymentService $paymentService,
        Request $request
    ): Response {

        // todo : check referring uri

        $form = $this->createForm(PaymentTypeChoiceForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderObject = $paymentService->createNewOrder();

            // save order to database
            $entityManager->persist($orderObject->getHeader());
            $entityManager->persist($orderObject->getItems());
            $entityManager->persist($orderObject->getAddresses());

            //todo: validate payment details
            // verify payment done
            // create

            $paymentTransactionId = "dfsdfaasasa";
            // upon success

            $orderPaymentDetails = $paymentService->createPaymentEntity(
                $orderObject->getHeader()->getId(), $paymentTransactionId
            );

            $entityManager->persist($orderPaymentDetails);

            $paymentService->postOrderSuccessCleanup();

            return $this->redirectToRoute(
                'web_shop_order_complete_details', ['id' => $orderObject->getHeader()->getId()]
            );
        }
        return $this->render('@SilecustWebShop/common/payment_type/payment_type_create.html.twig', ['form' => $form]
        );
    }


}