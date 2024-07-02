<?php

namespace App\Controller\Security\External\Credentials;

use App\Event\Security\External\SignUp\SignUpEvent;
use App\Event\Security\SecurityEventTypes;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Form\Security\User\DTO\SignUpSimpleDTO;
use App\Form\Security\User\SignUpAdvancedForm;
use App\Form\Security\User\SignUpSimpleForm;
use App\Service\MasterData\Customer\Mapper\CustomerDTOMapper;
use App\Service\Security\User\Customer\CustomerService;
use App\Service\Security\User\Mapper\SignUpDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController extends AbstractController
{

    /**
     * @param Request         $request
     * @param CustomerService $customerService
     * @param SignUpDTOMapper $signUpDTOMapper
     *
     * @return Response
     *
     * To be called when user quickly wants to sign up
     */
    #[Route('/signup', name: 'user_customer_sign_up')]
    public function signUp(Request $request,
        CustomerService $customerService,
        SignUpDTOMapper $signUpDTOMapper,
        EventDispatcherInterface $eventDispatcher

    ): Response {
        $signUpDTO = new SignUpSimpleDTO();

        $form = $this->createForm(SignUpSimpleForm::class, $signUpDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var SignUpSimpleDTO $data */
            $data = $form->getData();

            $user = $signUpDTOMapper->mapToEntityForCreate($data);

            $customer = $customerService->mapCustomerFromSimpleSignUp($user);

            $customerService->save($customer);

            $event = new SignUpEvent();
            $event->setCustomer($customer);

            $eventDispatcher->dispatch($event,SecurityEventTypes::POST_CUSTOMER_SIGN_UP_SUCCESS);

            // do anything else you need here, like send an email
            if ($request->get('_redirect_after_success') == null) {
                return $this->redirectToRoute('home');
            } else {
                return $this->redirectToRoute($request->get('_redirect_after_success'));
            }
        }

        return $this->render('security/external/user/sign_up/page/sign_up_page.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @param CustomerDTOMapper      $customerDTOMapper
     * @param EntityManagerInterface $entityManager
     *
     * @param Request                $request
     *
     * @return Response
     *
     * To be called when user is willing to add extra details for example from a checkout form
     */
    #[Route('/signup/advanced', name: 'user_customer_sign_up_advanced')]
    // Todo: Make redirect_after_success mandatory in route
    public function signUpAdvanced(CustomerDTOMapper $customerDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {


        $customerDTO = new CustomerDTO();
        $form = $this->createForm(
            SignUpAdvancedForm::class, $customerDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $customerEntity = $customerDTOMapper->mapToEntityForCreate($form);


            // perform some action...
            $entityManager->persist($customerEntity);
            $entityManager->flush();


            $id = $customerEntity->getId();

            $this->addFlash(
                'success', "Sign Up Successful"
            );

            return $this->redirectToRoute($request->get('_redirect_after_success'));

        }

        return $this->render(
            'security/external/user/sign_up/sign_up_advanced.html.twig',
            ['form' => $form]
        );

    }
}
