<?php

namespace App\Event\Module\WebShop\External\Product\Twig;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class SingleProductInProductListDisplayEvent extends Event
{

    const string SINGLE_PRODUCT_DISPLAY = 'web_shop.product_list.single_product_display';
    private mixed $data;

    private Product $product;

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getEntity(): mixed
    {
        return $this->data['entity'];
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }



}