<?php

namespace App\Service\Module\WebShop\External\Order\Mapper\Components;

use App\Entity\OrderHeader;
use App\Repository\OrderItemRepository;
use App\Repository\ProductRepository;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;

class OrderItemMapper
{
    public function __construct(private readonly CartSessionProductService $cartSessionService,
        private readonly OrderItemRepository $orderItemRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function mapAndSetHeader(OrderHeader $orderHeader): array
    {

        $orderItems = [];

        foreach ($this->cartSessionService->getCartArray() as $item) {

            $orderItem = $this->orderItemRepository->create($orderHeader);

            $orderItem->setQuantity($item->quantity);

            $product = $this->productRepository->find($item->productId);

            $orderItem->setProduct($product);

            $orderItems[] = $orderItem;
        }
        return $orderItems;
    }

    public function createOrderItem(OrderHeader $orderHeader, \App\Entity\Product $product,
        int $quantity
    ): \App\Entity\OrderItem {
        $orderItem = $this->orderItemRepository->create($orderHeader);

        $orderItem->setQuantity($quantity);

        $orderItem->setProduct($product);
        return $orderItem;

    }
}