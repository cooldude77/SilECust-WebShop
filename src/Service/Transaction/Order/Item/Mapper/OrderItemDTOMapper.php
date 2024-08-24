<?php

namespace App\Service\Transaction\Order\Item\Mapper;

use App\Entity\OrderItem;
use App\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use App\Repository\OrderHeaderRepository;
use App\Repository\OrderItemRepository;
use App\Repository\ProductRepository;
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
     */
    public function __construct(
        private OrderItemRepository $orderItemRepository,
        private OrderHeaderRepository $orderHeaderRepository,
        private ProductRepository $productRepository) {
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

//        $priceObject = $this->priceBreakUp->getPriceObject($product);
        // todo : what to do with the price object

  //      $pricePerUnit = $this->priceCalculator->calculatePrice($priceObject);

        return $this->orderItemRepository->create($orderHeader, $product, $orderItemDTO->quantity);


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