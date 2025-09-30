<?php

namespace Silecust\WebShop\Service\MasterData\Product\Image\Mapper;

use Silecust\WebShop\Entity\ProductImage;
use Silecust\WebShop\Form\Common\File\Mapper\FileDTOMapper;
use Silecust\WebShop\Form\MasterData\Product\Image\DTO\ProductImageDTO;
use Silecust\WebShop\Repository\ProductImageRepository;
use Silecust\WebShop\Repository\ProductRepository;

readonly class ProductImageDTOMapper
{

    public function __construct(private FileDTOMapper          $fileDTOMapper,
                                private ProductRepository      $productRepository,
                                private ProductImageRepository $productImageRepository
    )
    {
    }

    public function mapDtoToEntityForCreate(ProductImageDTO $productImageDTO): ProductImage
    {

        $product = $this->productRepository->find($productImageDTO->productId);

        $file = $this->fileDTOMapper->mapToFileEntityForCreate($productImageDTO->fileDTO);

        return $this->productImageRepository->create($product, $file);

    }

    public function mapDtoToEntityForEdit(ProductImageDTO $fileImageDTO,
                                          ProductImage    $productImage
    ): ProductImage
    {


        $file = $this->fileDTOMapper->mapToFileEntityForEdit(
            $fileImageDTO->fileDTO,
            $productImage->getFile()
        );

        $productImage->setFile($file);

        return $productImage;


    }

    public function mapEntityToDtoForEdit(ProductImage $productImage): ProductImageDTO
    {
        $productImageDTO = new ProductImageDTO();

        $productImageDTO->id = $productImage->getId();
        $productImageDTO->fileDTO = $this->fileDTOMapper->mapEntityToFileDto($productImage->getFile());

        return $productImageDTO;

    }

}