<?php

namespace Silecust\WebShop\Service\MasterData\Product\Mapper;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Form\MasterData\Product\DTO\ProductDTO;
use Silecust\WebShop\Repository\CategoryRepository;
use Silecust\WebShop\Repository\ProductRepository;

class ProductDTOMapper
{

    public function __construct(private readonly ProductRepository $productRepository, private readonly CategoryRepository $categoryRepository)
    {
    }

    public function mapToEntityForCreate(ProductDTO $productDTO): Product
    {
        /** @var Category $category */

        $product = $this->productRepository->create($this->categoryRepository->find($productDTO->categoryId));

        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $product->setIsActive($productDTO->isActive == true);

        return $product;
    }


    public function mapToEntityForEdit(ProductDTO $productDTO): ?Product
    {

        $product = $this->productRepository->find($productDTO->id);

        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $product->setCategory($this->categoryRepository->find($productDTO->categoryId));
        $product->setIsActive($productDTO->isActive);

        return $product;

    }

    public function mapToDtoFromEntityForEdit(Product $product): ProductDTO
    {

        $productDTO = new ProductDTO();


        $productDTO->id = $product->getId();
        $productDTO->name = $product->getName();
        $productDTO->description = $product->getDescription();
        $productDTO->categoryId = $product->getCategory()->getId();

        return $productDTO;

    }
}