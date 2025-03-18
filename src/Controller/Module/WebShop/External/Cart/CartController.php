<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart;
use Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException;
use Silecust\WebShop\Exception\Security\User\UserNotLoggedInException;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\CartMultipleEntryForm;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\CartSingleEntryForm;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeaderController;
use Silecust\WebShop\Service\Component\UI\Panel\PanelMainController;
use Silecust\WebShop\Service\MasterData\Price\PriceByCountryCalculator;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Mapper\CartSessionToDTOMapper;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Silecust\WebShop\Service\Security\User\Customer\CustomerFromUserFinder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 */
class  CartController extends EnhancedAbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/cart', name: 'module_web_shop_cart')]
    public function main(Request $request): Response
    {


        $session = $request->getSession();

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME, 'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME, 'list'
        );

        $session->set(
            PanelMainController::BASE_TEMPLATE,
            '@SilecustWebShop/module/web_shop/external/cart/page/cart_page.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    /**
     *
     * @param CartSessionToDTOMapper    $cartDTOMapper
     * @param CartSessionProductService $cartService
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param Request                   $request
     *
     * @return Response
     * @throws UserNotLoggedInException
     * @throws UserNotAssociatedWithACustomerException
     */
    public function list(CartSessionToDTOMapper $cartDTOMapper,
        CartSessionProductService $cartService, EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder, Request $request
    ): Response {

        $this->initializeCartAndDispatchEvents(
            $cartService, $eventDispatcher, $customerFromUserFinder
        );


        $DTOArray = $cartDTOMapper->mapCartToDto($cartService->getCartArray());

        $form = $this->createForm(CartMultipleEntryForm::class, ['items' => $DTOArray]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if ($form->get('checkout')->isClicked()) {
                // todo : check cart empty
                // todo: check cart not updated
                return $this->redirectToRoute('web_shop_checkout');
            }

            /** @var ArrayCollection $array */
            $array = $form->getData()['items'];
            $cartService->updateItemArray($array);

            $eventDispatcher->dispatch(
                new CartEvent(
                    $customerFromUserFinder->getLoggedInCustomer()
                ), CartEventTypes::POST_CART_QUANTITY_UPDATED
            );


        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/cart_list.html.twig', ['form' => $form]
        );
    }

    /**
     * @param CartSessionProductService $cartSessionProductService
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     *
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    private function initializeCartAndDispatchEvents(CartSessionProductService $cartSessionProductService,
        EventDispatcherInterface $eventDispatcher, CustomerFromUserFinder $customerFromUserFinder
    ): void {
        $cartSessionProductService->initialize();
        //todo handle exception`
        $eventDispatcher->dispatch(
            new CartEvent(
                $customerFromUserFinder->getLoggedInCustomer()
            ), CartEventTypes::POST_CART_INITIALIZED
        );


    }

    /**
     * @param                           $id
     * @param ProductRepository         $productRepository
     * @param CartSessionProductService $cartService
     * @param Request                   $request
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param RouterInterface           $router
     *
     * @return Response
     * @throws ProductNotFoundInCart
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    #[Route('/cart/product/{id}/add', name: 'module_web_shop_cart_add_product')]
    public function addToCart($id, ProductRepository $productRepository,
        CartSessionProductService $cartService, Request $request,
        EventDispatcherInterface $eventDispatcher, CustomerFromUserFinder $customerFromUserFinder,
        RouterInterface $router
    ): Response {


        if ($request->isMethod(Request::METHOD_POST)) {
            // When a non-logged-in user presses add to cart button
            if (!$customerFromUserFinder->isLoggedInUserAlsoACustomer()) {
                return $this->redirectToRoute('app_login');
            }
        }


        $product = $productRepository->find($id);

        $cartProductDTO = new CartProductDTO();
        $cartProductDTO->productId = $product->getId();
        $cartProductDTO->quantity = 1;

        $form = $this->createForm(
            CartSingleEntryForm::class, $cartProductDTO,
            ['action' => $router->generate('module_web_shop_cart_add_product', ['id' => $id])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->initializeCartAndDispatchEvents(
                $cartService, $eventDispatcher, $customerFromUserFinder
            );

            // Before Item was to be added to the cart
            $eventDispatcher->dispatch(
                new CartItemAddedEvent(
                    $customerFromUserFinder->getLoggedInCustomer(), $product,
                    $cartProductDTO->quantity
                ), CartEventTypes::BEFORE_ITEM_ADDED_TO_CART
            );

            $cartProductDTO = $form->getData();

            $cartObject = new CartSessionObject(
                $cartProductDTO->productId, $cartProductDTO->quantity
            );

            $cartService->addItemToCart($cartObject);

            // Todo : event after cart update
            $eventDispatcher->dispatch(
                new CartItemAddedEvent(
                    $customerFromUserFinder->getLoggedInCustomer(), $product,
                    $cartProductDTO->quantity
                ), CartEventTypes::ITEM_ADDED_TO_CART
            );


            return $this->redirectToRoute('module_web_shop_cart');

        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/add_to_cart_form.html.twig', ['form' => $form]
        );
    }

    /**
     *
     * @param                           $id
     * @param ProductRepository         $productRepository
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param CartSessionProductService $cartService
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    #[Route('/cart/product/{id}/delete', name: 'module_web_shop_cart_delete_product')]
    public function delete($id, ProductRepository $productRepository,
        EventDispatcherInterface $eventDispatcher, CustomerFromUserFinder $customerFromUserFinder,
        CartSessionProductService $cartService
    ): Response {

        $product = $productRepository->find($id);

        $cartService->initialize();
        $cartService->deleteItem($id);

        $eventDispatcher->dispatch(
            new CartItemDeletedEvent(
                $customerFromUserFinder->getLoggedInCustomer(), $product
            ), CartEventTypes::ITEM_DELETED_FROM_CART
        );

        if ($cartService->hasItems()) {
            return $this->redirectToRoute('home');
        } else {
            return $this->redirectToRoute('module_web_shop_cart');
        }
    }

    /**
     *
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param CartSessionProductService $cartService
     *
     * @return Response
     * @throws UserNotAssociatedWithACustomerException
     * @throws UserNotLoggedInException
     */
    #[Route('/cart/clear', name: 'module_web_shop_cart_clear')]
    public function clear(EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder, CartSessionProductService $cartService
    ): Response {

        $cartService->initialize();
        $cartService->clearCart();

        $eventDispatcher->dispatch(
            new CartClearedByUserEvent($customerFromUserFinder->getLoggedInCustomer()),
            CartEventTypes::CART_CLEARED_BY_USER
        );


        return $this->redirectToRoute('home');

    }

    /**
     * @throws ProductNotFoundInCart
     * @throws PriceProductBaseNotFound
     */
    public function single(string $id, ProductRepository $productRepository,
        CartSessionProductService $cartSessionService,
        PriceByCountryCalculator $priceByCountryCalculator,
    ): Response {


        $product = $productRepository->find($id);

        $quantity = $cartSessionService->getQuantity($id);


        $unitPrice = $priceByCountryCalculator->getPriceWithoutTax($id);

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/cart_single_product.html.twig', ['product' => $product,
                                                                            'unitPrice' => $unitPrice,
                                                                            'quantity' => $quantity,
                                                                            'currency' => $priceByCountryCalculator->getCurrency(
                                                                            )]
        );
    }


}