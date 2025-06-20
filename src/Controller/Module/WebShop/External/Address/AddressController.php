<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Address;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressChosenEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Address\AddressCreatedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Framework\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Exception\Module\WebShop\External\Address\NoAddressChosenAtCheckout;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Form\MasterData\Customer\Address\CustomerAddressCreateForm;
use Silecust\WebShop\Form\MasterData\Customer\Address\DTO\CustomerAddressDTO;
use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\AddressChooseFromMultipleForm;
use Silecust\WebShop\Form\Module\WebShop\External\Address\Existing\DTO\AddressChooseExistingMultipleDTO;
use Silecust\WebShop\Form\Module\WebShop\External\Address\New\AddressCreateForm;
use Silecust\WebShop\Form\Module\WebShop\External\Address\New\DTO\AddressCreateAndChooseDTO;
use Silecust\WebShop\Repository\CustomerAddressRepository;
use Silecust\WebShop\Service\Component\Routing\RoutingConstants;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Silecust\WebShop\Service\MasterData\Customer\Address\CustomerAddressDTOMapper;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckoutAddressChooseParser;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressQuery;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSave;
use Silecust\WebShop\Service\Module\WebShop\External\Address\CheckOutAddressSession;
use Silecust\WebShop\Service\Module\WebShop\External\Address\Mapper\Existing\ChooseFromMultipleAddressDTOMapper;
use Silecust\WebShop\Service\Module\WebShop\External\Address\Mapper\New\CreateNewAndChooseDTOMapper;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class AddressController extends EnhancedAbstractController
{


    /**
     * @param Request $request
     * @param RouterInterface $router
     *
     * @return Response
     */
    #[Route('/checkout/addresses', name: 'sc_web_shop_checkout_addresses')]
    #[Route('/checkout/address/create', name: 'sc_web_shop_checkout_address_create')]
    #[Route('/checkout/addresses/choose', name: 'sc_web_shop_checkout_choose_address_from_list')]
    public function main(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $session = $request->getSession();


        $eventDispatcher->dispatch(
            new PreHeadForwardingEvent($request),
            PreHeadForwardingEvent::EVENT_NAME
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_NAME, HeadController::class
        );
        $session->set(
            PanelHeadController::HEAD_CONTROLLER_CLASS_METHOD_NAME, 'head'
        );

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );

        $route = $request->get('_route');
        $method = "";
        switch ($route) {
            case 'sc_web_shop_checkout_addresses':
                $method = "content";
                break;
            case 'sc_web_shop_checkout_address_create':
                $method = "create";
                break;
            case 'sc_web_shop_checkout_choose_address_from_list':
                $method = "choose";
                break;
        }

        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            $method
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            '@SilecustWebShop/module/web_shop/external/address/page/address_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);
    }

    /**
     * @param CustomerAddressRepository $customerAddressRepository
     * @param CheckOutAddressQuery $checkOutAddressQuery
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @return RedirectResponse
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */

    public function content(
        CustomerAddressRepository $customerAddressRepository,
        CheckOutAddressQuery      $checkOutAddressQuery,
        CustomerFromUserFinder    $customerFromUserFinder,
    ): RedirectResponse
    {

        $ownRoute = $this->generateUrl('sc_web_shop_checkout_addresses');
        $customer = $customerFromUserFinder->getLoggedInCustomer();

        $addressesShipping = $customerAddressRepository->findBy(['customer' => $customer,
            'addressType' => 'shipping']);

        if ($addressesShipping == null) {
            return $this->redirectToRoute(
                'sc_web_shop_checkout_address_create',
                ['type' => 'shipping',
                    RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                        'sc_web_shop_checkout_addresses'
                    )]
            );
        }

        $addressesBilling = $customerAddressRepository->findBy(['customer' => $customer,
            'addressType' => 'billing']);

        if ($addressesBilling == null) {
            return $this->redirectToRoute(
                'sc_web_shop_checkout_address_create',
                ['type' => 'billing',
                    RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                        'sc_web_shop_checkout_addresses'
                    )]
            );
        }

        // addresses exist but not yet chosen
        if (!$checkOutAddressQuery->isShippingAddressChosen()) {
            return $this->redirectToRoute(
                'sc_web_shop_checkout_choose_address_from_list', [
                    'type' => 'shipping',
                    RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                        'sc_web_shop_checkout_addresses'
                    )]
            );
        }


        if (!$checkOutAddressQuery->isBillingAddressChosen()) {
            return $this->redirectToRoute(
                'sc_web_shop_checkout_choose_address_from_list',
                ['type' => 'billing',
                    RoutingConstants::REDIRECT_UPON_SUCCESS_URL => $this->generateUrl(
                        'sc_web_shop_checkout_addresses'
                    )]
            );
        }


        // everything ok, go back to check out
        // recalculate prices etc
        return $this->redirectToRoute('sc_web_shop_checkout');


    }

    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function create11111(RouterInterface             $router, Request $request,
                                CustomerFromUserFinder      $customerFromUserFinder,
                                CreateNewAndChooseDTOMapper $createNewAndChooseDTOMapper,
                                CheckOutAddressSave         $checkOutAddressSave,
                                EventDispatcherInterface    $eventDispatcher
    ): Response
    {

        $dto = new AddressCreateAndChooseDTO();

        $dto->address->customerId = $customerFromUserFinder->getLoggedInCustomer()->getId();

        if ($request->get('type') != null) {
            $dto->address->addressType = $request->query->get('type');
        }

        $this->setContentHeading($request, $dto->address->addressType == 'shipping' ? 'Add Shipping Address' :
            'Add Billing Address');


        $x = $request->query->get('type');

        $form = $this->createForm(
            AddressCreateForm::class, $dto,
            ['addressType' => $request->query->get('type')]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var AddressCreateAndChooseDTO $data */
            $data = $form->getData();


            $address = $checkOutAddressSave->save(
                $createNewAndChooseDTOMapper->map($data),
                $createNewAndChooseDTOMapper->isChosen($data)
            );

            $eventDispatcher->dispatch(
                new AddressCreatedEvent(
                    $customerFromUserFinder->getLoggedInCustomer(),
                    $address,
                    $createNewAndChooseDTOMapper->isChosen($data)
                ),
                AddressCreatedEvent::EVENT_NAME
            );
            return $this->redirect(
                $request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL)
            );
        }
        // if it is a form then just display it raw here
        return $this->render(
            '@SilecustWebShop/admin/ui/panel/section/content/create/create.html.twig', ['form' => $form]
        );

    }

    /**
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    public function create(
        Request                  $request,
        CustomerFromUserFinder   $customerFromUserFinder,
        EntityManagerInterface   $entityManager,
        CustomerAddressDTOMapper $customerAddressDTOMapper,
        EventDispatcherInterface $eventDispatcher,
        CheckOutAddressSession   $checkOutAddressSession
    ): Response
    {


        $customerAddressDTO = new CustomerAddressDTO();
        $customerAddressDTO->customerId = $customerFromUserFinder->getLoggedInCustomer()->getId();


        $form = $this->createForm(
            CustomerAddressCreateForm::class, $customerAddressDTO
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CustomerAddressDTO $data */
            $data = $form->getData();
            $data->postalCodeId = $form->get('postalCode')->getData()->getId();

            $customerAddresses = $customerAddressDTOMapper->mapDtoToEntityForCreate($data);

            foreach ($customerAddresses as $customerAddress) {
                $eventDispatcher
                    ->dispatch(new AddressCreatedEvent($customerAddress), AddressCreatedEvent::EVENT_NAME);
                $entityManager->persist($customerAddress);
            }

            $entityManager->flush();

            // Cannot happen in event as the above process need to be completed successfully
            // post
            // todo: verify more
            foreach ($customerAddresses as $customerAddress) {
                $checkOutAddressSession->setSessionParameters($customerAddress);
            }

            return $this->redirect(
                $request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL)
            );

        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/address/address_create.html.twig', [
                'form' => $form,
                'addressType' => $request->query->get('addressType')
            ]
        );

    }

    /**
     *
     * @param CustomerAddressRepository $customerAddressRepository
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @param ChooseFromMultipleAddressDTOMapper $addressChooseMapper
     * @param CheckoutAddressChooseParser $checkoutAddressChooseParser
     * @param EventDispatcherInterface $eventDispatcher
     * @param Request $request
     *
     * @return Response
     * @throws UserNotLoggedInException Choose from multiple addresses
     * @throws UserNotAssociatedWithACustomerException
     */
    public function choose(CustomerAddressRepository          $customerAddressRepository,
                           CustomerFromUserFinder             $customerFromUserFinder,
                           ChooseFromMultipleAddressDTOMapper $addressChooseMapper,
                           CheckoutAddressChooseParser        $checkoutAddressChooseParser,
                           EventDispatcherInterface           $eventDispatcher,
                           Request                            $request
    ): Response
    {
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

                $this->addFlash('error', 'Please choose at least one address');
                return $this->redirectToRoute('sc_web_shop_checkout_choose_address_from_list');

            }

            $eventDispatcher->dispatch(
                new AddressChosenEvent($address),
                AddressChosenEvent::EVENT_NAME,

            );

            return $this->redirectToRoute('sc_web_shop_checkout_addresses');

        }

        if ($request->query->get('type') == 'shipping') {
            $caption = 'Choose Shipping Address';
        } else {
            $caption = 'Choose Billing Address';
        }
        return $this->render(
            '@SilecustWebShop/module/web_shop/external/address/address_choose.html.twig',
            ['form' => $form,
                'addressTypeCaption' => $caption]
        );
    }

}