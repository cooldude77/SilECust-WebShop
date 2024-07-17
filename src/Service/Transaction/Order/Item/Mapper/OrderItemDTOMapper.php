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

/**
 *
 */
readonly class OrderItemDTOMapper
{
    /**
     * @param CartSessionProductService $cartSessionService
     * @param OrderItemRepository       $orderItemRepository
     * @param OrderHeaderRepository     $orderHeaderRepository
     * @param ProductRepository         $productRepository
     * @param PriceCalculator           $calculator
     */
    public function __construct(
        private OrderItemRepository $orderItemRepository,
        private OrderHeaderRepository $orderHeaderRepository,
        private ProductRepository $productRepository,
        private PriceCalculator $calculator,
    ) {
    }


    /**
     * @param OrderItemDTO $orderItemDTO
     *
     * @return OrderItem
     */
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

    /**
     * @param OrderItem|null $orderItem
     *
     * @return OrderItemDTO
     */
    public function mapFromEntityToDtoForEdit(?OrderItem $orderItem): OrderItemDTO
    {

        $orderItemDTO = new OrderItemDTO();
        $orderItemDTO->id = $orderItem->getId();
        $orderItemDTO->quantity = $orderItem->getQuantity();
        return $orderItemDTO;
    }

    /**
     * @param OrderItemDTO $orderItemDTO
     *
     * @return OrderItem
     */
    public function mapDtoToEntityForEdit(OrderItemDTO  $orderItemDTO): OrderItem
    {
        $orderItem = $this->orderItemRepository->find($orderItemDTO->id);

        $orderItem->setQuantity($orderItemDTO->quantity);

        return $orderItem;
    }
}