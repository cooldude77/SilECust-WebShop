<?php

namespace Silecust\WebShop\Service\MasterData\Inventory\Mapper;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\InventoryProduct;
use Silecust\WebShop\Form\MasterData\Inventory\DTO\InventoryProductDTO;
use Silecust\WebShop\Repository\InventoryProductRepository;
use Silecust\WebShop\Repository\ProductRepository;
use Symfony\Component\Form\FormInterface;

class InventoryProductDTOMapper
{
    public function __construct(readonly private InventoryProductRepository $inventoryRepository,
        readonly private ProductRepository $productRepository
    ) {
    }

    public function mapToEntityForCreate(InventoryProductDTO $inventoryProductDTO): InventoryProduct
    {
        /** @var Category $category */
        $product = $this->productRepository->find($inventoryProductDTO->productId);

        $inventoryProduct = $this->inventoryRepository->create($product);

        $inventoryProduct->setQuantity($inventoryProductDTO->quantity);

        return $inventoryProduct;
    }


    public function mapToEntityForEdit(InventoryProductDTO $inventoryProductDTO):InventoryProduct
    {
        $inventoryProduct = $this->inventoryRepository->find($inventoryProductDTO->id);

        $inventoryProduct->setQuantity($inventoryProductDTO->quantity);

        return $inventoryProduct;

    }
}