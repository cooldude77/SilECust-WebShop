<?php
// src/Controller/CustomerController.php
namespace App\Controller\MasterData\Customer\Address;

// ...
use App\Form\MasterData\Customer\Address\CustomerAddressCreateForm;
use App\Form\MasterData\Customer\Address\CustomerAddressEditForm;
use App\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use App\Repository\CustomerAddressRepository;
use App\Service\MasterData\Customer\Address\CustomerAddressDTOMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerAddressController extends AbstractController
{

    #[Route('/customer/{id}/address/create', name: 'customer_address_create')]
    public function create(int $id, CustomerAddressDTOMapper $mapper,
        EntityManagerInterface $entityManager, Request $request
    ): Response {
        $customerAddressDTO = new CustomerAddressDTO();

        $customerAddressDTO->customerId = $id;
        if ($request->get('type') != null) {
            $customerAddressDTO->addressType = $request->get('type');
        }

        $form = $this->createForm(
            CustomerAddressCreateForm::class, $customerAddressDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->pinCodeId = $form->get('pinCode')->getData()->getId();

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
            '/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }


    #[\Symfony\Component\Routing\Attribute\Route('/customer/address/{id}/edit', name: 'customer_address_edit')]
    public function edit(int $id, CustomerAddressDTOMapper $mapper,
        EntityManagerInterface $entityManager,
        CustomerAddressRepository $customerAddressRepository, Request $request
    ): Response {
        $customerAddressDTO = new CustomerAddressDTO();

        $customerAddress = $customerAddressRepository->find($id);

        $form = $this->createForm(CustomerAddressEditForm::class, $customerAddressDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->pinCodeId = $form->get('pinCode')->getData()->getId();

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
            '/admin/ui/panel/section/content/edit/edit.html.twig', ['form' => $form]
        );

    }

    #[Route('/customer/address/{id}/display', name: 'customer_address_display')]
    public function display(CustomerAddressRepository $customerAddressRepository, int $id): Response
    {
        $customerAddress = $customerAddressRepository->find($id);
        if (!$customerAddress) {
            throw $this->createNotFoundException('No Customer found for id ' . $id);
        }

        $displayParams = ['title' => 'Customer Address',
                          'link_id' => 'id-customer-address',
                          'editButtonLinkText' => 'Edit',
                          'fields' => [['label' => 'line 1',
                                        'propertyName' => 'line-1',
                                        'link_id' => 'id-display-customer-address'],
                          ]];

        return $this->render(
            'master_data/customer/customer_display.html.twig',
            ['entity' => $customerAddress, 'params' => $displayParams]
        );

    }


    #[Route('/customer/{id}/address/list', name: 'customer_address_list')]
    public function list(int $id, CustomerAddressRepository $customerAddressRepository
    ): Response {

        $listGrid = ['title' => 'Customer Address',
                     'link_id' => 'id-customer-address',
                     'columns' => [['label' => 'Address Line 1',
                                    'propertyName' => 'line1',
                                    'action' => 'display']
                                   ,],
                     'createButtonConfig' => ['link_id' => 'id-create-customer-address',
                                              'function' => 'customer_address',
                                              'anchorText' => 'Create Customer Address']];

        $customerAddresses = $customerAddressRepository->findBy(
            ['customer' => $id]
        );
        return $this->render(
            'admin/ui/panel/section/content/list/list.html.twig',
            ['entities' => $customerAddresses, 'listGrid' => $listGrid]
        );
    }

}