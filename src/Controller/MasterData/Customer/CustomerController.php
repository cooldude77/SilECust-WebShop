<?php
// src/controller/customerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Form\MasterData\Customer\CustomerCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\CustomerEditForm;
use Silecust\WebShop\Form\MasterData\Customer\DTO\CustomerDTO;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Service\MasterData\Customer\Mapper\CustomerDTOMapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends EnhancedAbstractController
{


    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);
    }

    #[Route('/admin/customer/create', 'customer_create')]
    public function create(CustomerDTOMapper      $customerDTOMapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $customerDTO = new CustomerDTO();
        $form = $this->createForm(CustomerCreateForm::class, $customerDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $customerEntity = $customerDTOMapper->mapToEntityForCreate($form->getData());


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
        return $this->render('@SilecustWebShop/master_data/customer/customer_create.html.twig', ['form' => $form]);
    }


    #[Route('/admin/customer/{id}/edit', name: 'sc_admin_customer_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         CustomerRepository     $customerRepository,
                         CustomerDTOMapper      $customerDTOMapper,
                         Request                $request,
                         int                    $id
    ): Response
    {
        $customer = $customerRepository->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $customerDTO = $customerDTOMapper->mapToDTOForEdit($customer);

        $form = $this->createForm(CustomerEditForm::class, $customerDTO);
        //     $this->addRedirectUrl($form, $request->attributes->get(CommonIdentificationConstants::REDIRECT_UPON_SUCCESS_URL));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $customerDTOMapper->mapToEntityForEdit($form->getData(), $customer);
            // perform some action...
            $entityManager->persist($customer);
            $entityManager->flush();

            $this->addFlash(
                'success', "Customer updated successfully"
            );
            return new JsonResponse(['id' => $id, 'message' => "Customer updated successfully"], 200);
        }

        return $this->render('@SilecustWebShop/master_data/customer/customer_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/customer/{id}/display', name: 'sc_admin_customer_display')]
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
            '@SilecustWebShop/master_data/customer/customer_display.html.twig',
            ['entity' => $customer, 'params' => $displayParams]
        );

    }

    #[Route('/admin/customer/list', name: 'sc_admin_customer_list')]
    public function list(CustomerRepository $customerRepository, Request $request): Response
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
            '@SilecustWebShop/admin/ui/panel/section/content/list/list.html.twig',
            ['request' => $request, 'entities' => $customers, 'listGrid' => $listGrid]
        );
    }


}