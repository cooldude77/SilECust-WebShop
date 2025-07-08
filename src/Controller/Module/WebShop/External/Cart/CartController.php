<?php

namespace Silecust\WebShop\Controller\Module\WebShop\External\Cart;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeadController;
use Silecust\WebShop\Controller\Module\WebShop\External\Common\Components\HeaderController;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use Silecust\WebShop\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use Silecust\WebShop\Event\Module\WebShop\External\Framework\Head\PreHeadForwardingEvent;
use Silecust\WebShop\Exception\MasterData\Pricing\Item\PriceProductBaseNotFound;
use Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\CartMultipleEntryForm;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\CartSingleEntryForm;
use Silecust\WebShop\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelContentController;
use Silecust\WebShop\Service\Component\UI\Panel\Components\PanelHeadController;
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
    // todo: write test cases when transaction rollback happens
    /**
     * @throws Exception
     */
    #[Route('/cart', name: 'sc_module_web_shop_cart')]
    public function main(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {


        $session = $request->getSession();

        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_NAME, HeaderController::class
        );
        $session->set(
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME, 'header'
        );
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
     * @param CartSessionToDTOMapper $cartDTOMapper
     * @param CartSessionProductService $cartService
     * @param EventDispatcherInterface $eventDispatcher
     * @param Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return Response
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\CartItemToUpdateIsOfInvalidType
     * @throws \Silecust\WebShop\Exception\Security\User\Customer\UserNotAssociatedWithACustomerException
     * @throws \Silecust\WebShop\Exception\Security\User\UserNotLoggedInException
     * @throws \Exception
     */
    public function list(
        CartSessionToDTOMapper    $cartDTOMapper,
        CartSessionProductService $cartService,
        EventDispatcherInterface  $eventDispatcher,
        Request                   $request,
        EntityManagerInterface    $entityManager
    ): Response
    {

        $this->initializeCartAndDispatchEvents($cartService, $eventDispatcher, $entityManager);

        $DTOArray = $cartDTOMapper->mapCartToDto($cartService->getCartArray());

        $form = $this->createForm(CartMultipleEntryForm::class, ['items' => $DTOArray]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if ($form->get('checkout')->isClicked()) {
                // todo : check cart empty
                // todo: check cart not updated
                return $this->redirectToRoute('sc_web_shop_checkout');
            }

            $arrayOfCartItems = [];
            /** @var CartProductDTO $item */
            foreach ($form->getData()['items'] as $item) {

                $arrayOfCartItems[] = new CartSessionObject($item->productId, $item->quantity);
            }
            $cartService->updateItemArray($arrayOfCartItems);

            $eventDispatcher->dispatch(new CartEvent(), CartEventTypes::POST_CART_QUANTITY_UPDATED);


        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/cart_list.html.twig', ['form' => $form]
        );
    }

    /**
     * @param CartSessionProductService $cartSessionProductService
     * @param EventDispatcherInterface $eventDispatcher
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @throws \Exception
     */
    private function initializeCartAndDispatchEvents(
        CartSessionProductService $cartSessionProductService,
        EventDispatcherInterface  $eventDispatcher,
        EntityManagerInterface    $entityManager,
    ): void
    {

        // session variable initialization
        $cartSessionProductService->initialize();
        try {

            $entityManager->beginTransaction();

            // the order is created here
            $eventDispatcher->dispatch(new CartEvent(), CartEventTypes::POST_CART_INITIALIZED);

            $entityManager->flush();
            $entityManager->commit();
        } catch (Exception $exception) {

            $entityManager->rollback();
            throw $exception;
        }
    }

    /**
     * @param                           $id
     * @param ProductRepository $productRepository
     * @param CartSessionProductService $cartService
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @param CustomerFromUserFinder $customerFromUserFinder
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param RouterInterface $router
     *
     * @return Response
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart
     * @throws \Exception
     */
    #[Route('/cart/product/{id}/add', name: 'sc_module_web_shop_cart_add_product')]
    public function addToCart($id,
                              ProductRepository $productRepository,
                              CartSessionProductService $cartService,
                              Request $request,
                              EventDispatcherInterface $eventDispatcher,
                              CustomerFromUserFinder $customerFromUserFinder,
                              EntityManagerInterface $entityManager,
                              RouterInterface $router
    ): Response
    {

        if ($request->isMethod(Request::METHOD_POST)) {
            // When a non-logged-in user presses add to cart button
            if (!$customerFromUserFinder->isLoggedInUserACustomer()) {
                return $this->redirectToRoute('sc_app_login');
            }
        }

        $this->initializeCartAndDispatchEvents($cartService, $eventDispatcher, $entityManager);

        $product = $productRepository->find($id);

        $cartProductDTO = new CartProductDTO();
        $cartProductDTO->productId = $product->getId();
        $cartProductDTO->quantity = 1;

        $form = $this->createForm(
            CartSingleEntryForm::class, $cartProductDTO,
            ['action' => $router->generate('sc_module_web_shop_cart_add_product', ['id' => $id])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $eventDispatcher->dispatch(
                new CartItemAddedEvent($product, $cartProductDTO->quantity),
                CartEventTypes::BEFORE_ITEM_ADDED_TO_CART
            );

            $cartProductDTO = $form->getData();

            // NEW
            $cartObject = new CartSessionObject($cartProductDTO->productId, $cartProductDTO->quantity);
            $cartService->addItemToCart($cartObject);

            // Now raise events for persistence and other stuff
            try {

                $entityManager->beginTransaction();

                $eventDispatcher->dispatch(new CartItemAddedEvent($product,$cartProductDTO->quantity), CartEventTypes::ITEM_ADDED_TO_CART);

                $entityManager->flush();
                $entityManager->commit();
            } catch (Exception $exception) {

                $cartService->deleteItem($cartObject->productId);

                $entityManager->rollback();
                throw $exception;
            }
            return $this->redirectToRoute('sc_module_web_shop_cart');

        }

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/add_to_cart_form.html.twig', ['form' => $form]
        );
    }

    /**
     *
     * @param int $id
     * @param ProductRepository $productRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param CartSessionProductService $cartService
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return Response
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart
     */
    #[Route('/cart/product/{id}/delete', name: 'sc_module_web_shop_cart_delete_product')]
    public function delete(
        int                       $id,
        ProductRepository         $productRepository,
        EventDispatcherInterface  $eventDispatcher,
        CartSessionProductService $cartService,
        EntityManagerInterface    $entityManager
    ): Response
    {

        $product = $productRepository->find($id);

        $cartService->initialize();

        $cartObject = $cartService->getCartObjectByKey($id);
        $cartService->deleteItem($id);
        try {

            $entityManager->beginTransaction();

            $eventDispatcher->dispatch(
                new CartItemDeletedEvent($product), CartEventTypes::ITEM_DELETED_FROM_CART
            );
            $entityManager->flush();
            $entityManager->commit();

        } catch (Exception $exception) {

            $cartService->addItemToCart($cartObject);
            $entityManager->rollback();

            throw $exception;
        }
        if ($cartService->hasItems()) {
            return $this->redirectToRoute('sc_home');
        } else {
            return $this->redirectToRoute('sc_module_web_shop_cart');
        }
    }

    /**
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param CartSessionProductService $cartService
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return Response
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart
     */
    #[Route('/cart/clear', name: 'sc_module_web_shop_cart_clear')]
    public function clear(
        EventDispatcherInterface  $eventDispatcher,
        CartSessionProductService $cartService,
        EntityManagerInterface    $entityManager
    ): Response
    {

        $cartService->initialize();
        $cartArray = $cartService->getCartArray();
        $cartService->clearCart();

        try {
            $entityManager->beginTransaction();

            $eventDispatcher->dispatch(
                new CartClearedByUserEvent(),
                CartEventTypes::CART_CLEARED_BY_USER);

            $entityManager->flush();
            $entityManager->commit();
        } catch (Exception $exception) {

            foreach ($cartArray as $arr)
                $cartService->addItemToCart($arr);
            $entityManager->rollback();
            throw $exception;
        }

        return $this->redirectToRoute('sc_home');

    }

    /**
     * @throws ProductNotFoundInCart
     * @throws PriceProductBaseNotFound
     */
    public function single(string                    $id, ProductRepository $productRepository,
                           CartSessionProductService $cartSessionService,
                           PriceByCountryCalculator  $priceByCountryCalculator,
    ): Response
    {


        $product = $productRepository->find($id);

        $quantity = $cartSessionService->getQuantity($id);


        $unitPrice = $priceByCountryCalculator->getPriceWithoutTax($id);

        return $this->render(
            '@SilecustWebShop/module/web_shop/external/cart/cart_single_product.html.twig', ['product' => $product,
                'unitPrice' => $unitPrice,
                'quantity' => $quantity,
                'currency' => $priceByCountryCalculator->getCurrency()]
        );
    }


}