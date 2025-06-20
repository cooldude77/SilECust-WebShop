<?php /** @noinspection ALL */

// src/Controller/CustomerController.php
namespace Silecust\WebShop\Controller\MasterData\Customer\Address;

// ...
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Entity\CustomerAddress;
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
    public function create(
        int                      $id,
        EventDispatcherInterface $eventDispatcher,
        CustomerAddressDTOMapper $mapper,
        EntityManagerInterface   $entityManager,
        Request                  $request
    ): Response
    {


        $customerAddressDTO = new CustomerAddressDTO();
        $customerAddressDTO->customerId = $id;

        $form = $this->createForm(
            CustomerAddressCreateForm::class, $customerAddressDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->postalCodeId = $form->get('postalCode')->getData()->getId();

            $customerAddress = $mapper->mapDtoToEntityForCreate($data);


            foreach ($customerAddress as $address) {

                $entityManager->persist($address);
            }
            $entityManager->flush();
            $a = $entityManager->getRepository(CustomerAddress::class)->findAll();
            $this->addFlash(
                'success', "Customer Address created successfully"
            );

            // $id = $customerAddress->getId();

            return new Response(
                serialize(
                    ['message' => "Customer Address(es) created successfully"]
                ), 201
            );

        }

        $errors = $form->getErrors(true);

        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[Route('/admin/customer/address/{id}/edit', name: 'sc_admin_customer_address_edit')]
    public function edit(int                      $id,
                         CustomerAddressDTOMapper $customerAddressDTOMapper,
                         EntityManagerInterface   $entityManager,
                         Request                  $request
    ): Response
    {
        $this->setContentHeading($request, 'Edit Address');

        $customerAddressDTO = $customerAddressDTOMapper->mapEntityToDtoForUpdate($id);

        $form = $this->createForm(CustomerAddressEditForm::class, $customerAddressDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
// todo : In display actually check if value of postalcode changed
            $customerAddress = $customerAddressDTOMapper->mapDtoToEntityForUpdate($data);

            $entityManager->persist($customerAddress);
            $entityManager->flush();


            $id = $customerAddress->getId();
            $this->addFlash(
                'success', "Address updated successfully"
            );

            return new Response(
                serialize(
                    ['id' => $id, 'message' => "Customer Address updated successfully"]
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
        $this->setContentHeading($request, 'Display Address');

        $customerAddress = $customerAddressRepository->find($id);

        if (!$customerAddress) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        // NOTE: This grid can be called as a subsection to main screen
        $displayParamsEvent = $eventDispatcher->dispatch(new DisplayParamPropertyEvent($request), DisplayParamPropertyEvent::EVENT_NAME);


        return $this->render(
            '@SilecustWebShop/master_data/customer/address/customer_address_display.html.twig',
            ['entity' => $customerAddress, 'params' => $displayParamsEvent->getDisplayParamProperties(), 'request' => $request]
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
        int $id,
        Request                  $request,
        PaginatorInterface       $paginator,
        EventDispatcherInterface $eventDispatcher,

    ): Response
    {
        // if the grid is a secondary
        // do not set top heading
        if ($request->attributes->get('twig_is_grid_secondary') != true)
            $this->setContentHeading($request, 'Addresses');

        // NOTE: This grid can be called as a subsection to main screen
        $listGridEvent = $eventDispatcher->dispatch(new GridPropertyEvent($request, [
            'event_caller' => $this::LIST_IDENTIFIER,
            'id' => $id,
        ]), GridPropertyEvent::EVENT_NAME);

        $listQueryEvent = $eventDispatcher->dispatch(new ListQueryEvent($request,
            [
                'event_caller' => $this::LIST_IDENTIFIER,
                'id' => $id,
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