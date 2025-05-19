<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Cart\Session;

use Silecust\WebShop\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Handling product functionality in cart
 */
class CartSessionProductService
{
    /**
     *
     */
    public final const string CART_SESSION_KEY = '_WEB_SHOP_CART';

    /**
     * @var Session
     */
    private SessionInterface $session;

    /**
     * @param RequestStack      $requestStack
     * @param ProductRepository $productRepository
     */
    public function __construct(private readonly RequestStack $requestStack) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
        $x=0;
    }

    /**
     * @param CartSessionObject $cartObject
     *
     * @return void
     * @throws ProductNotFoundInCart
     */
    public function addItemToCart(CartSessionObject $cartObject): void
    {
        // Todo: check quantity proper values

        // todo: check product
        //if($this->productRepository->find($productId)==null)
        //  throw new NoSuchProductException($id);

        if($this->isProductInCartArray($cartObject->productId)) {
            $cartObjectInSession = $this->getCartObjectByKey($cartObject->productId);
            $cartObjectInSession->quantity += 1;
            $this->setCartObject($cartObjectInSession);
        }
        else{
            $this->setCartObject($cartObject);
        }


    }

    /**
     * @param CartSessionObject $cartObject
     *
     * @return void
     */
    private function setCartObject(CartSessionObject $cartObject): void
    {
        $array = $this->getCartArray();
        // todo: validations
        $array[$cartObject->productId] = $cartObject;

        $this->setCartArrayInSession($array);

    }

    /**
     * @param int $productId
     *
     * @return CartSessionObject
     * @throws ProductNotFoundInCart
     */
    private function getCartObjectByKey(int $productId): CartSessionObject{

        if($this->isProductInCartArray($productId))
            return $this->getCartArray()[$productId];
        else
            throw new ProductNotFoundInCart($productId);
    }

    /**
     * @param int $productId
     *
     * @return bool
     */
    private function isProductInCartArray(int $productId):bool{

        return in_array($productId,array_keys($this->getCartArray()));
    }

    /**
     * @return array
     */
    public function getCartArray(): array
    {
        $this->initialize();

        $x = $this->session->get(self::CART_SESSION_KEY);

        return $x;
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->session = $this->requestStack->getMainRequest()->getSession();

        if (empty($this->session->get(self::CART_SESSION_KEY))) {
            $this->setCartArrayInSession();
        }
    }

    /**
     * @param array $array
     *
     * @return void
     */
    private function setCartArrayInSession(array $array = []): void
    {
        // always serialize
        $this->session->set(self::CART_SESSION_KEY, $array);
        $this->session->save();
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        $this->session =  $this->requestStack->getMainRequest()->getSession();

        return !empty($this->session->get(self::CART_SESSION_KEY));

    }

    /**
     * @return void
     */
    public function clearCart(): void
    {
        $this->initialize();
        $this->setCartArrayInSession([]);
        $this->session->remove(self::CART_SESSION_KEY);
        $this->session->save();

    }

    /**
     * @param ArrayCollection $array
     *
     * @return void
     */
    public function updateItemArray(ArrayCollection $array): void
    {
        $this->initialize();
        $cartArray = $this->getCartArray();
        /** CartSessionObject $item */
        foreach ($array as $item) {
            $cartArray[$item->productId] = $item;
        }
        $this->setCartArrayInSession($cartArray);
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function deleteItem($id): void
    {
        $this->initialize();

        $cartArray = $this->getCartArray();
        unset($cartArray[$id]);

        $this->setCartArrayInSession($cartArray);

    }

    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        $this->initialize();
        return !empty($this->getCartArray());
    }


    /**
     * @param string $id
     *
     * @return int
     * @throws ProductNotFoundInCart
     */
    public function getQuantity(string $id): int
    {

        if (!array_key_exists($id, $this->getCartArray())) {
            throw new ProductNotFoundInCart($id);
        }

        return $this->getCartArray()[$id]->quantity;
    }

    /**
     * @return bool
     */
    public function isCartEmpty(): bool
    {

        return count($this->getCartArray()) == 0;
    }


}