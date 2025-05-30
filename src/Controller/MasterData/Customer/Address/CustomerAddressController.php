<?php /** @noinspection ALL */

// src/Controller/CustomerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer\Address;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
use Silecust\WebShop\Event\Component\UI\Panel\Display\DisplayParamPropertyEvent;
use Silecust\WebShop\Event\Component\UI\Panel\List\GridPropertyEvent;
use Silecust\WebShop\Exception\MasterData\Customer\Address\AddressTypeNotProvided;
use Silecust\WebShop\Form\MasterData\Customer\Address\CustomerAddressCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\CustomerAddressEditForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\MasterData\Customer\Address\CustomerAddressDTOMapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerAddressController extends EnhancedAbstractController
{
    const string LIST_IDENTIFIER = "address_grid";
    /**
     * @throws AddressTypeNotProvided
     */
    #[Route('/admin/customer/{id}/address/create', name: 'sc_admin_customer_address_create')]
    public function create(int                    $id, CustomerAddressDTOMapper $mapper,
                           EntityManagerInterface $entityManager, Request $request
    ): Response
    {
        $customerAddressDTO = new CustomerAddressDTO();

        $customerAddressDTO->customerId = $id;

        if ($request->query->get('type') != null)
            $customerAddressDTO->addressType = $request->query->get('type');


        $form = $this->createForm(
            CustomerAddressCreateForm::class, $customerAddressDTO,
            ['addressType' => $request->query->get('type')]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->postalCodeId = $form->get('postalCode')->getData()->getId();

            $customerAddress = $mapper->mapDtoToEntityForCreate($data);

            $entityManager->persist($customerAddress);
            $entityManager->flush();

            $this->addFlash(
                'success', "Customer Address created successfully"
            );

            $id = $customerAddress->getId();

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer Address created successfully"]
                ), 201
            );

        }

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[Route('/admin/customer/address/{id}/edit', name: 'sc_admin_customer_address_edit')]
    public function edit(int                       $id, CustomerAddressDTOMapper $mapper,
                         EntityManagerInterface    $entityManager,
                         CustomerAddressRepository $customerAddressRepository, Request $request
    ): Response
    {
        $customerAddressDTO = new CustomerAddressDTO();

        $customerAddress = $customerAddressRepository->find($id);

        $form = $this->createForm(CustomerAddressEditForm::class, $customerAddressDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->postalCodeId = $form->get('postalCode')->getData()->getId();

            $customerEntity = $mapper->mapDtoToEntityForUpdate(
                $data, $customerAddress
            );

            $entityManager->persist($customerEntity);
            $entityManager->flush();


            $id = $customerEntity->getId();
            $this->addFlash(
                'success', "Customer Address Value updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer Address Value updated successfully"]
                ), 200
            );
        }

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }

    #[Route('/admin/customer/address/{id}/display', name: 'sc_admin_customer_address_display')]
    public function display(
        CustomerAddressRepository $customerAddressRepository,
        int                       $id,
        Request                   $request,
        EventDispatcherInterface  $eventDispatcher): Response
    {
        $customerAddress = $customerAddressRepository->find($id);
        if (!$customerAddress) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }
        $displayParamEvent = $eventDispatcher->dispatch(
            new DisplayParamPropertyEvent($request),
            DisplayParamPropertyEvent::EVENT_NAME);
        /*
                $displayParams = ['title' => 'Customer Address',
                    'link_id' => 'id-customer-address',
                    'editButtonLinkText' => 'Edit',
                    'fields' => [
                        ['label' => 'line 1',
                            'propertyName' => 'line1',
                            'link_id' => 'id-display-customer-address'],
                    ]];
        */
        return $this->render(
            '@SilecustWebShop/master_data/customer/customer_display.html.twig',
            ['entity' => $customerAddress, 'params' => $displayParamEvent->getDisplayParamProperties(), 'request' => $request]
        );

    }

    #[Route('/admin/customer/address/{id}/delete', name: 'sc_admin_customer_address_delete')]
    public function delete(CustomerAddressRepository $customerAddressRepository, int $id): Response
    {
        $customerAddress = $customerAddressRepository->find($id);
        if (!$customerAddress) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $customerAddressRepository->remove($customerAddress);

        return new JsonResponse(['id' => $id, 'message' => "Customer Address Delete"]);
    }


    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    #[Route('/admin/customer/{id}/address/list', name: 'sc_admin_customer_address_list')]
    public function list(
        Request                  $request,
        PaginatorInterface       $paginator,
        EventDispatcherInterface $eventDispatcher,

    ): Response
    {
        $this->setContentHeading($request, 'Addresses');

        // NOTE: This grid can be called as a subsection to main screen
        $listGridEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request, [
            'event_caller' => $this::LIST_IDENTIFIER
        ]), GridPropertyEvent::EVENT_NAME);

        $listQueryEvent = $eventDispatcher->dispatch(new ListQueryEvent($request,
            [
                'event_caller' => $this::LIST_IDENTIFIER
            ]), ListQueryEvent::BEFORE_LIST_QUERY);

        $query = $listQueryEvent->getQuery();

        // todo : to bring price ( calculated field on the list)
        $pagination = $paginator->paginate(
            $query, /* query NOT result */ $request->query->getInt('page', 1),
            /*page number*/ 10 /*limit per page*/
        );
        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/list/list_paginated.html.twig',
            ['pagination' => $pagination, 'listGrid' => $listGridEvent->getListGridProperties(), 'request' => $request]
        );
    }

}