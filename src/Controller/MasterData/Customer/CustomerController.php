<?php
// src/controller/customerController.php
namespace App\Controller\MasterData\Customer;

// ...
use App\Form\MasterData\Customer\CustomerCreateForm;
use App\Form\MasterData\Customer\CustomerEditForm;
use App\Form\MasterData\Customer\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use App\Service\MasterData\Customer\Mapper\CustomerDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{

    #[\Symfony\Component\Routing\Attribute\Route('/customer/create', 'customer_create')]
    public function create(CustomerDTOMapper $customerDTOMapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $customerDTO = new CustomerDTO();
        $form = $this->createForm(
            CustomerCreateForm::class, $customerDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $customerEntity = $customerDTOMapper->mapToEntityForCreate($form);


            // perform some action...
            $entityManager->persist($customerEntity);
            $entityManager->flush();


            $id = $customerEntity->getId();

            $this->addFlash(
                'success', "Customer created successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer created successfully"]
                ), 200
            );
        }

        $formErrors = $form->getErrors(true);
        return $this->render('master_data/customer/customer_create.html.twig', ['form' => $form]);
    }


    #[Route('/customer/{id}/edit', name: 'customer_edit')]
    public function edit(EntityManagerInterface $entityManager,
        CustomerRepository $customerRepository, CustomerDTOMapper $customerDTOMapper,
        Request $request, int $id
    ): Response {
        $customer = $customerRepository->find($id);


        if (!$customer) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $customerDTO = new CustomerDTO();
        $customerDTO->id = $id;

        $form = $this->createForm(CustomerEditForm::class, $customerDTO);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $customerDTOMapper->mapToEntityForEdit($form, $customer);
            // perform some action...
            $entityManager->persist($customer);
            $entityManager->flush();

            $id = $customer->getId();

            $this->addFlash(
                'success', "Customer updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer updated successfully"]
                ), 200
            );
        }

        return $this->render('master_data/customer/customer_edit.html.twig', ['form' => $form]);
    }

    #[Route('/customer/{id}/display', name: 'customer_display')]
    public function display(CustomerRepository $customerRepository, int $id): Response
    {
        $customer = $customerRepository->find($id);
        if (!$customer) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $displayParams = ['title' => 'Customer',
                          'link_id' => 'id-customer',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'First Name',
                                        'propertyName' => 'firstName',
                                        'link_id' => 'id-display-customer'],
                                       ['label' => 'Last Name',
                                        'propertyName' => 'lastName'],]];

        return $this->render(
            'master_data/customer/customer_display.html.twig',
            ['entity' => $customer, 'params' => $displayParams]
        );

    }

    #[\Symfony\Component\Routing\Attribute\Route('/customer/list', name: 'customer_list')]
    public function list(CustomerRepository $customerRepository): Response
    {

        $listGrid = ['title' => 'Customer',
                     'link_id' => 'id-customer',
                     'columns' => [['label' => 'Name',
                                    'propertyName' => 'firstName',
                                    'action' => 'display',],],
                     'createButtonConfig' => ['link_id' => ' id-create-Customer',
                                              'function' => 'customer',
                                              'anchorText' => 'create Customer']];

        $customers = $customerRepository->findAll();
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $customers, 'listGrid' => $listGrid]
        );
    }
}