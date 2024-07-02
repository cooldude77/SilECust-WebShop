<?php

namespace App\Service\Module\WebShop\External\Cart\Session;

use App\Exception\Module\WebShop\External\Cart\Session\ProductNotFoundInCart;
use App\Repository\ProductRepository;
use App\Service\Module\WebShop\External\Cart\Session\Object\CartSessionObject;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Handling product functionality in cart
 */
class CartSessionProductService
{
    public final const string CART_SESSION_KEY = '_WEB_SHOP_CART';

    private Session $session;

    public function __construct(private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    public function addItemToCart(CartSessionObject $cartObject): void
    {
        // Todo: check quantity proper values

        // todo: check product
        //if($this->productRepository->find($productId)==null)
        //  throw new NoSuchProductException($id);

        $this->setCartObject($cartObject);

    }

    private function setCartObject(CartSessionObject $cartObject): void
    {
        $array = $this->getCartArray();
        // todo: validations
        $array[$cartObject->productId] = $cartObject;

        $this->setCartArrayInSession($array);

    }

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
        $this->session = $this->requestStack->getSession();

        if (empty($this->session->get(self::CART_SESSION_KEY))) {
            $this->setCartArrayInSession();
        }
    }

    private function setCartArrayInSession(array $array = []): void
    {
        // always serialize
        $this->session->set(self::CART_SESSION_KEY, $array);
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        $this->session = $this->requestStack->getSession();

        return !empty($this->session->get(self::CART_SESSION_KEY));

    }

    public function clearCart(): void
    {
        $this->initialize();
        $this->setCartArrayInSession([]);
        $this->session->remove(self::CART_SESSION_KEY);

    }

    public function updateItemArray(\Doctrine\Common\Collections\ArrayCollection $array): void
    {
        $this->initialize();
        $cartArray = $this->getCartArray();
        /** CartSessionObject $item */
        foreach ($array as $item) {
            $cartArray[$item->productId] = $item;
        }
        $this->setCartArrayInSession($cartArray);
    }

    public function deleteItem($id)
    {
        $this->initialize();

        $cartArray = $this->getCartArray();
        unset($cartArray[$id]);

        $this->setCartArrayInSession($cartArray);

    }

    public function hasItems(): bool
    {
        $this->initialize();
        return !empty($this->getCartArray());
    }


    public function getQuantity(string $id): int
    {

        if (!array_key_exists($id, $this->getCartArray())) {
            throw new ProductNotFoundInCart($id);
        }

        return $this->getCartArray()[$id]->quantity;
    }

    public function isCartEmpty(): bool
    {

        return count($this->getCartArray()) == 0;
    }


}