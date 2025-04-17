<?php
// src/Controller/CustomerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer\Address;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Event\Component\Database\ListQueryEvent;
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
            '@SilecustWebShop/common/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }

    #[Route('/admin/customer/address/{id}/display', name: 'sc_admin_customer_address_display')]
    public function display(CustomerAddressRepository $customerAddressRepository, int $id): Response
    {
        $customerAddress = $customerAddressRepository->find($id);
        if (!$customerAddress) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $displayParams = ['title' => 'Customer Address',
            'link_id' => 'id-customer-address',
            'editButtonLinkText' => 'Edit',
            'fields' => [
                ['label' => 'line 1',
                    'propertyName' => 'line-1',
                    'link_id' => 'id-display-customer-address'],
            ]];

        return $this->render(
            '@SilecustWebShop/master_data/customer/customer_display.html.twig',
            ['entity' => $customerAddress, 'params' => $displayParams]
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


    #[Route('/admin/customer/{id}/address/list', name: 'sc_admin_customer_address_list')]
    public function list(int                       $id,
                         CustomerAddressRepository $customerAddressRepository,
                         Request                   $request,
                         PaginatorInterface        $paginator,
                         EventDispatcherInterface  $eventDispatcher,

    ): Response
    {

        $listGrid = [
            'title' => 'Customer Address',
            'function' => 'customer_address',
            'link_id' => 'id-customer-address',
            'columns' => [
                ['label' => 'Address Line 1',
                    'propertyName' => 'line1',
                    'action' => 'display'
                ]
                ,],
            'editButtonLinkText' => 'Edit',
            'edit_link_allowed' => true,
            'createButtonConfig' => [
                'link_id' => 'id-create-customer-address',
                'function' => 'customer_address',
                'anchorText' => 'Create Customer Address'
            ]
        ];


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