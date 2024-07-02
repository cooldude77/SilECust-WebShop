<?php

namespace App\Service\Module\WebShop\External\Cart;

use App\Entity\PriceProductBase;
use App\Repository\PriceProductBaseRepository;
use App\Repository\ProductRepository;

class PriceService
{

    public function __construct(private readonly ProductRepository $productRepository,
        private readonly PriceProductBaseRepository $priceProductBaseRepository,
    ) {
    }

    public function getPrice(string $id): PriceProductBase
    {
        // todo:// validation
        $product = $this->productRepository->find($id);
        $price = $this->priceProductBaseRepository->findOneBy(['product' => $product]);

        return $price;

    }
}