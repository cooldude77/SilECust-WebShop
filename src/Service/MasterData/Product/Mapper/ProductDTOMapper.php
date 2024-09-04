<?php

namespace App\Service\MasterData\Product\Mapper;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\MasterData\Product\DTO\ProductDTO;
use App\Repository\CategoryRepository;
use App\Repository\ProductGroupRepository;
use App\Repository\ProductRepository;

class ProductDTOMapper
{

    public function __construct(
        private readonly ProductRepository      $productRepository,
        private readonly CategoryRepository     $categoryRepository,
        private readonly ProductGroupRepository $productGroupRepository)
    {
    }

    public function mapToEntityForCreate(ProductDTO $productDTO): Product
    {
        /** @var Category $category */

        $product = $this->productRepository->create($this->categoryRepository->find($productDTO->categoryId));


        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $productGroup = $this->productGroupRepository->findOneBy(['id' => $productDTO->productGroupId]);
        $product->setProductGroup($productGroup);

        return $product;
    }


    public function mapToEntityForEdit(ProductDTO $productDTO): ?Product
    {

        $product = $this->productRepository->find($productDTO->id);

        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $product->setCategory($this->categoryRepository->find($productDTO->categoryId));
        $productGroup = $this->productGroupRepository->findOneBy(['id' => $productDTO->productGroupId]);
        $product->setProductGroup($productGroup);

        return $product;

    }

    public function mapToDtoFromEntityForEdit(Product $product): ProductDTO
    {

        $productDTO = new ProductDTO();


        $productDTO->id = $product->getId();
        $productDTO->name = $product->getName();
        $productDTO->description = $product->getDescription();
        $productDTO->categoryId = $product->getCategory()->getId();
        $productDTO->productGroupId = $product->getProductGroup() != null ?? $product->getProductGroup()->getId();

        return $productDTO;

    }
}