<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

// src/controller/customerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertySetEvent;
use Silecust\WebShop\Form\MasterData\Customer\CustomerCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\CustomerEditForm;
use Silecust\WebShop\Form\MasterData\Customer\DTO\CustomerDTO;
use Silecust\WebShop\Repository\CustomerRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Silecust\WebShop\Service\MasterData\Customer\Mapper\CustomerDTOMapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends EnhancedAbstractController
{


    const string LIST_IDENTIFIER = 'address';

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);
    }

    #[Route('/admin/customer/create', 'sc_admin_customer_create')]
    public function create(
        CustomerDTOMapper      $customerDTOMapper,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $this->setContentHeading($request, "Create Customer");


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

        return $this->render('@SilecustWebShop/master_data/customer/customer_create.html.twig', [
            'form' => $form, 'request' => $request]);
    }


    #[Route('/admin/customer/{id}/edit', name: 'sc_admin_customer_edit')]
    public function edit(EntityManagerInterface $entityManager,
                         CustomerRepository     $customerRepository,
                         CustomerDTOMapper      $customerDTOMapper,
                         Request                $request,
                         int                    $id
    ): Response
    {
        $this->setContentHeading($request,"Edit Customer");
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
            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer updated successfully"]
                ), 200
            );
        }

        return $this->render('@SilecustWebShop/master_data/customer/customer_edit.html.twig', ['form' => $form]);
    }

    #[Route('/admin/customer/{id}/display', name: 'sc_admin_customer_display')]
    public function display(CustomerRepository $customerRepository, int $id, Request $request): Response
    {
        $this->setContentHeading($request,"Display Customer");
        $customer = $customerRepository->find($id);
        if (!$customer) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $displayParams = [
            'title' => 'Customer',
            'link_id' => 'id-customer',
            'config' => [
                'edit_link' => [
                    'editButtonLinkText' => 'Edit',
                    'route' => 'sc_my_address_edit',
                    'link_id' => 'id-display-customer-address']
            ],
            'fields' => [
                [
                    'label' => 'First Name',
                    'propertyName' => 'firstName',
                    'link_id' => 'id-display-customer'
                ],
                [
                    'label' => 'Last Name',
                    'propertyName' => 'lastName'
                ],
            ]
        ];

        return $this->render(
            '@SilecustWebShop/master_data/customer/customer_display.html.twig',
            ['entity' => $customer, 'params' => $displayParams, 'request' => $request]
        );

    }

    /**
     * @throws QueryException
     */
    #[Route('/admin/customer/list', name: 'sc_admin_customer_list')]
    public function list(CustomerRepository    $customerRepository,
                         PaginatorInterface    $paginator,
                         SearchEntityInterface $searchEntity,
                         Request               $request): Response
    {

        // Todo : The addition of properties causes grid overflow.
        // todo: find a solution for overflow
        $this->setContentHeading($request, 'Customers');

        $listGridEvent = $this->eventDispatcher->dispatch(new GridPropertySetEvent($request,
            ['event_caller' => $this::LIST_IDENTIFIER]), GridPropertySetEvent::EVENT_NAME);


        $query = $searchEntity->getQueryForSelect($request, $customerRepository,
            ['firstName', 'middleName', 'lastName', 'givenName']);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGridEvent->getListGridProperties(), 'request' => $request]
        );
    }


}