<?php

namespace App\Service\Transaction\Order\Item\Mapper;

use App\Entity\OrderHeader;
use App\Entity\OrderItem;
use App\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderItemRepository;
use App\Repository\ProductRepository;
use App\Service\MasterData\Pricing\PriceCalculator;
use App\Service\Module\WebShop\External\Cart\Session\CartSessionProductService;

readonly class OrderItemDTOMapper
{
    public function __construct(private CartSessionProductService $cartSessionService,
        private OrderItemRepository $orderItemRepository,
        private OrderHeaderRepository $orderHeaderRepository,
        private ProductRepository $productRepository,
        private PriceCalculator $calculator,
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


    public function mapToEntityForCreate(OrderItemDTO $orderItemDTO): OrderItem
    {
        $product = $this->productRepository->find($orderItemDTO->productId);
        $orderHeader = $this->orderHeaderRepository->find($orderItemDTO->orderHeaderId);

        $priceObject = $this->calculator->getPriceObject($product);
        // todo : what to do with the price object

        $pricePerUnit = $this->calculator->calculatePrice($priceObject);

        return $this->orderItemRepository->create(
            $orderHeader, $product, $orderItemDTO->quantity,
            $pricePerUnit
        );


    }
}