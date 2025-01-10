<?php

namespace App\Controller\Module\WebShop\External\Address;

use App\Controller\Component\Routing\RoutingConstants;
use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
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
 use App\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class AddressController extends EnhancedAbstractController
{


    /**
     * @param Request         $request
     * @param RouterInterface $router
     *
     * @return Response
     */
    #[Route('/checkout/addresses', name: 'web_shop_checkout_addresses')]
    #[Route('/checkout/address/create', name: 'web_shop_checkout_address_create')]
    #[Route('/checkout/addresses/choose', name: 'web_shop_checkout_choose_address_from_list')]
    public function main(Request $request, RouterInterface $router): Response
    {
        $session = $request->getSession();

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
        switch ($route) {
            case 'web_shop_checkout_addresses':
                $method = "content";
                break;
            case 'web_shop_checkout_address_create':
                $method = "create";
                break;
            case 'web_shop_checkout_choose_address_from_list':
                $method = "choose";
                break;
        }
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            $method
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            'module/web_shop/external/address/page/address_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);
    }


    public function content(CustomerAddressRepository $customerAddressRepository,
        CheckOutAddressQuery $checkOutAddressQuery,
        CustomerFromUserFinder $customerFromUserFinder,
        Request $request
    ): \Symfony\Component\HttpFoundation\RedirectResponse {

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
        if ($request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL) != null) {
            return $this->redirect(
                $request->query->get(RoutingConstants::REDIRECT_UPON_SUCCESS_URL)
            );
        } else {
            return $this->redirectToRoute('web_shop_view_order');
        }


    }

    public function create(RouterInterface $router, Request $request,
        CustomerFromUserFinder $customerFromUserFinder,
        CreateNewAndChooseDTOMapper $createNewAndChooseDTOMapper,
        CheckOutAddressSave $checkOutAddressSave,
        EventDispatcherInterface $eventDispatcher
    ): Response {


        $dto = new AddressCreateAndChooseDTO();

        $dto->address->customerId = $customerFromUserFinder->getLoggedInCustomer()->getId();

        if ($request->get('type') != null) {
            $dto->address->addressType = $request->query->get('type');
        }

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
     *
     * @param CustomerAddressRepository          $customerAddressRepository
     * @param CustomerFromUserFinder             $customerFromUserFinder
     * @param ChooseFromMultipleAddressDTOMapper $addressChooseMapper
     * @param CheckoutAddressChooseParser        $checkoutAddressChooseParser
     * @param EventDispatcherInterface           $eventDispatcher
     * @param Request                            $request
     *
     * @return Response
     * @throws UserNotLoggedInException Choose from multiple addresses
     * @throws UserNotAssociatedWithACustomerException
     */
    public function choose(CustomerAddressRepository $customerAddressRepository,
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

                $this->addFlash('error', 'Please choose at least one address');
                return $this->redirectToRoute('web_shop_checkout_choose_address_from_list');

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

        if ($request->query->get('type') == 'shipping') {
            $caption = 'Choose Shipping Address';
        } else {
            $caption = 'Choose Billing Address';
        }
        return $this->render(
            'module/web_shop/external/address/address_choose.html.twig',
            ['form' => $form,
             'addressTypeCaption' => $caption]
        );
    }

}