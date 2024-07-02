<?php

namespace App\Controller\Module\WebShop\External\Address;

use App\Controller\Component\Routing\RoutingConstants;
use App\Event\Module\WebShop\External\Address\CheckoutAddressChosenEvent;
use App\Event\Module\WebShop\External\Address\CheckoutAddressCreatedEvent;
use App\Event\Module\WebShop\External\Address\Types\CheckoutAddressEventTypes;
use App\Exception\Module\WebShop\External\Address\NoAddressChosenAtCheckout;
use App\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Form\Module\WebShop\External\Address\Existing\AddressChooseFromMultipleForm;
use App\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use App\Form\Module\WebShop\External\Address\New\AddressCreateForm;
use App\Form\Module\WebShop\External\Address\New\DTO\AddressCreateAndChooseDTO;
use App\Repository\CustomerAddressRepository;
use App\Service\Module\WebShop\External\Address\CheckoutAddressChooseParser;
use App\Service\Module\WebShop\External\Address\CheckOutAddressQuery;
use App\Service\Module\WebShop\External\Address\CheckOutAddressSave;
use App\Service\Module\WebShop\External\Address\Mapper\Existing\ChooseFromMultipleAddressDTOMapper;
use App\Service\Module\WebShop\External\Address\Mapper\New\CreateNewAndChooseDTOMapper;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class AddressController extends AbstractController
{


    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    #[Route('/checkout/addresses', name: 'web_shop_checkout_addresses')]
    public function main(
        CustomerAddressRepository $customerAddressRepository,
        CheckOutAddressQuery $checkOutAddressQuery,
        CustomerFromUserFinder $customerFromUserFinder,
        Request $request
    ): Response {

        $ownRoute = $this->generateUrl('web_shop_checkout_addresses');
        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $addressesShipping = $customerAddressRepository->findBy(['customer' => $customer,
                                                                 'addressType' => 'shipping']);

        if ($addressesShipping == null) {
            return $this->redirectToRoute(
                'web_shop_checkout_address_create',
                ['type' => 'shipping',
                 RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                     'web_shop_checkout_addresses'
                 )]
            );
        }

        $addressesBilling = $customerAddressRepository->findBy(['customer' => $customer,
                                                                'addressType' => 'billing']);

        if ($addressesBilling == null) {
            return $this->redirectToRoute(
                'web_shop_checkout_address_create',
                ['type' => 'billing',

                 RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                     'web_shop_checkout_addresses'
                 )]
            );
        }

        // addresses exist but not yet chosen
        if (!$checkOutAddressQuery->isShippingAddressChosen()) {
            return $this->redirectToRoute(
                'web_shop_checkout_choose_address_from_list', [
                    'type' => 'shipping',
                    RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                        'web_shop_checkout_addresses'
                    )]
            );
        }
        if (!$checkOutAddressQuery->isBillingAddressChosen()) {
            return $this->redirectToRoute(
                'web_shop_checkout_choose_address_from_list',
                ['type' => 'billing',
                 RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                     'web_shop_checkout_addresses'
                 )]
            );
        }

        // everything ok, go back to check out
        return $this->redirect($request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL));
    }

    #[Route('/checkout/address/create', name: 'web_shop_checkout_address_create')]
    public function create(RouterInterface $router, Request $request,
        CustomerFromUserFinder $customerFromUserFinder,
        CreateNewAndChooseDTOMapper $createNewAndChooseDTOMapper,
        CheckOutAddressSave $checkOutAddressSave,
        EventDispatcherInterface $eventDispatcher
    ): Response {


        $dto = new AddressCreateAndChooseDTO();

        $dto->address->customerId = $customerFromUserFinder->getLoggedInCustomer()->getId();

        if ($request->get('type') != null) {
            $dto->address->addressType = $request->get('type');
        }

        $form = $this->createForm(AddressCreateForm::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var AddressCreateAndChooseDTO $data */
            $data = $form->getData();


            $address = $checkOutAddressSave->save(
                $createNewAndChooseDTOMapper->map($data),
                $createNewAndChooseDTOMapper->isChosen($data)
            );

            $eventDispatcher->dispatch(
                new CheckoutAddressCreatedEvent(
                    $customerFromUserFinder->getLoggedInCustomer(),
                    $address,
                    $createNewAndChooseDTOMapper->isChosen($data)
                ),
                CheckoutAddressEventTypes::POST_ADDRESS_CREATE
            );
            return $this->redirect(
                $request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL)
            );
        }
        // if it is a form then just display it raw here
        return $this->render(
            'admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }

    /**
     * @param CustomerAddressRepository          $customerAddressRepository
     * @param CustomerFromUserFinder             $customerFromUserFinder
     * @param ChooseFromMultipleAddressDTOMapper $addressChooseMapper
     * @param Request                            $request
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException Choose from multiple addresses
     */
    #[Route('/checkout/addresses/choose', name: 'web_shop_checkout_choose_address_from_list')]
    public function chooseAddressFromList(CustomerAddressRepository $customerAddressRepository,
        CustomerFromUserFinder $customerFromUserFinder,
        ChooseFromMultipleAddressDTOMapper $addressChooseMapper,
        CheckoutAddressChooseParser $checkoutAddressChooseParser,
        EventDispatcherInterface $eventDispatcher,
        Request $request
    ): Response {
        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $addresses = $customerAddressRepository->findBy(['customer' => $customer,
                                                         'addressType' => $request->query->get(
                                                             'type'
                                                         )]);

        $addressesDTO = $addressChooseMapper->mapAddressesToDto(
            $addresses, $request->request->all()
        );


        $form = $this->createForm(AddressChooseFromMultipleForm::class, $addressesDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var AddressChooseExistingMultipleDTO $data */
            $data = $form->getData();

            try {
                $address = $checkoutAddressChooseParser->setAddressInSession(
                    $data, $request->query->get('type')
                );
            } catch (NoAddressChosenAtCheckout $e) {

                $this->addFlash('error','Please choose at least one address');
                return $this->redirectToRoute('web_shop_checkout_choose_address_from_list') ;

            }

            $eventDispatcher->dispatch(
                new CheckoutAddressChosenEvent(
                    $customerFromUserFinder->getLoggedInCustomer(),
                    $address
                ),
                CheckoutAddressEventTypes::POST_ADDRESS_CHOSEN,

            );

            return $this->redirectToRoute('web_shop_checkout_addresses');

        }


        return $this->render(
            'module/web_shop/external/checkout/address/page/checkout_address_chooser_page.html.twig',
            ['form' => $form,
             'addressTypeCaption' => 'Shipping Address']
        );
    }

}