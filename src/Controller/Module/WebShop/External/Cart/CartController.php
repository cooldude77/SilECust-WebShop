<?php

namespace App\Controller\Module\WebShop\External\Cart;

use App\Controller\Component\UI\Panel\Components\PanelContentController;
use App\Controller\Component\UI\Panel\Components\PanelHeaderController;
use App\Controller\Component\UI\PanelMainController;
use App\Controller\Module\WebShop\External\Shop\HeaderController;
use App\Event\Module\WebShop\External\Cart\CartClearedByUserEvent;
use App\Event\Module\WebShop\External\Cart\CartEvent;
use App\Event\Module\WebShop\External\Cart\CartItemAddedEvent;
use App\Event\Module\WebShop\External\Cart\CartItemDeletedEvent;
use App\Event\Module\WebShop\External\Cart\Types\CartEventTypes;
use App\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart;
use App\Exception\Security\User\UserNotLoggedInException;
use App\Form\Module\WebShop\External\Cart\CartMultipleEntryForm;
use App\Form\Module\WebShop\External\Cart\CartSingleEntryForm;
use App\Form\Module\WebShop\External\Cart\DTO\CartProductDTO;
use App\Repository\ProductRepository;
use App\Service\Module\WebShop\External\Cart\PriceService;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;
use App\Service\Module\WebShop\External\Cart\Session\Mapper\CartSessionToDTOMapper;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use App\Service\Security\User\Customer\CustomerFromUserFinder;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class  CartController extends AbstractController
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
            PanelHeaderController::HEADER_CONTROLLER_CLASS_METHOD_NAME,
            'header'
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_NAME, self::class
        );
        $session->set(
            PanelContentController::CONTENT_CONTROLLER_CLASS_METHOD_NAME,
            'list'
        );
        $session->set(
            PanelMainController::BASE_TEMPLATE,
            'module/web_shop/external/base/web_shop_base_template.html.twig'
        );


        return $this->forward(PanelMainController::class . '::main', ['request' => $request]);

    }

    /**
     * @param CartSessionToDTOMapper    $cartDTOMapper
     * @param CartSessionProductService $cartService
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CustomerFromUserFinder    $customerFromUserFinder
     * @param Request                   $request
     *
     * @return Response
     * @throws UserNotLoggedInException
     */
    public function list(CartSessionToDTOMapper $cartDTOMapper,
        CartSessionProductService $cartService,
        EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder,
        Request $request
    ): Response {

        $this->initializeCartAndDispatchEvents(
            $cartService,
            $eventDispatcher, $customerFromUserFinder
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
                ),
                CartEventTypes::POST_CART_QUANTITY_UPDATED
            );


        }

        return $this->render(
            'module/web_shop/external/cart/cart_list.html.twig',
            [
                'form' => $form
            ]
        );
    }

    private function initializeCartAndDispatchEvents(CartSessionProductService $cartSessionProductService,
        EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder
    ): void {
        $cartSessionProductService->initialize();
        //todo handle exception`
        $eventDispatcher->dispatch(
            new CartEvent(
                $customerFromUserFinder->getLoggedInCustomer()
            ),
            CartEventTypes::POST_CART_INITIALIZED
        );


    }

    #[Route('/cart/product/{id}/add', name: 'module_web_shop_cart_add_product')]
    public function addToCart($id, ProductRepository $productRepository,
        CartSessionProductService $cartService,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder,
        RouterInterface $router
    ):
    Response {
        $this->initializeCartAndDispatchEvents(
            $cartService,
            $eventDispatcher, $customerFromUserFinder
        );

        $product = $productRepository->find($id);

        $cartProductDTO = new CartProductDTO();
        $cartProductDTO->productId = $product->getId();

        $form = $this->createForm(
            CartSingleEntryForm::class, $cartProductDTO,
            ['action' => $router->generate('module_web_shop_cart_add_product', ['id' => $id])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cartProductDTO = $form->getData();

            $cartObject = new CartSessionObject(
                $cartProductDTO->productId, $cartProductDTO->quantity
            );

            $cartService->addItemToCart($cartObject);

            // Todo : event after cart update
            $eventDispatcher->dispatch(
                new CartItemAddedEvent(
                    $customerFromUserFinder->getLoggedInCustomer(),
                    $product,
                    $cartProductDTO->quantity
                ),
                CartEventTypes::ITEM_ADDED_TO_CART
            );


            return new Response("Product Added Successfully");

        }

        $error = $form->getErrors(true);
        return $this->render(
            'module/web_shop/external/cart/add_to_cart_form.html.twig',
            ['form' => $form]
        );
    }

    #[Route('/cart/product/{id}/delete', name: 'module_web_shop_cart_delete_product')]
    public function delete($id, ProductRepository $productRepository,
        EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder,
        CartSessionProductService $cartService
    ):
    Response {


        $product = $productRepository->find($id);

        $cartService->initialize();
        $cartService->deleteItem($id);

        $eventDispatcher->dispatch(
            new CartItemDeletedEvent(
                $customerFromUserFinder->getLoggedInCustomer(),
                $product
            ),
            CartEventTypes::ITEM_DELETED_FROM_CART
        );


        return new Response("Item Deleted");
    }

    #[Route('/cart/clear', name: 'module_web_shop_cart_clear')]
    public function clear(
        EventDispatcherInterface $eventDispatcher,
        CustomerFromUserFinder $customerFromUserFinder,
        CartSessionProductService $cartService
    ):
    Response {

        $cartService->initialize();
        $cartService->clearCart();

        $eventDispatcher->dispatch(
            new CartClearedByUserEvent($customerFromUserFinder->getLoggedInCustomer()),
            CartEventTypes::CART_CLEARED_BY_USER
        );


        return new Response("Cart Cleared");

    }

    /**
     * @throws ProductNotFoundInCart
     */
    public function single(string $id, ProductRepository $productRepository,
        PriceService $priceService,
        CartSessionProductService $cartSessionService
    ):
    Response {

        $product = $productRepository->find($id);

        $quantity = $cartSessionService->getQuantity($id);

        $price = $priceService->getPrice($id);


        return $this->render(
            'module/web_shop/external/cart/cart_single_product.html.twig',
            [
                'product' => $product,
                'price' => $price,
                'quantity' => $quantity,
                'current' => $price->getCurrency()
            ]
        );
    }


}