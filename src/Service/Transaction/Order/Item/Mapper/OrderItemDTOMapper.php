<?php

namespace Silecust\WebShop\Service\Transaction\Order\Item\Mapper;

use Silecust\WebShop\Entity\OrderItem;
use Silecust\WebShop\Form\Transaction\Order\Item\DTO\OrderItemDTO;
use Silecust\WebShop\Repository\OrderHeaderRepository;
use Silecust\WebShop\Repository\OrderItemRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Module\WebShop\External\Cart\Product\Manager\CartProductManager;

/**
 *
 */
readonly class OrderItemDTOMapper
{
    /**
     * @param OrderItemRepository $orderItemRepository
     * @param OrderHeaderRepository $orderHeaderRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private OrderItemRepository   $orderItemRepository,
        private OrderHeaderRepository $orderHeaderRepository,
        private ProductRepository     $productRepository)
    {
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
        $orderItemDTO->productId = $orderItem->getProduct()->getId();

        return $orderItemDTO;
    }

    /**
     * @param OrderItemDTO $orderItemDTO
     *
     * @return OrderItem
     */
    public function mapDtoToEntityForEdit(OrderItemDTO $orderItemDTO): OrderItem
    {
        $orderItem = $this->orderItemRepository->find($orderItemDTO->id);

        $orderItem->setQuantity($orderItemDTO->quantity);

        return $orderItem;
    }
}