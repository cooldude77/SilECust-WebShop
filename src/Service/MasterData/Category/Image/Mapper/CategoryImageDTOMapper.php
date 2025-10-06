<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image\Mapper;

use Silecust\WebShop\Entity\CategoryImage;
use Silecust\WebShop\Form\Common\File\Mapper\FileDTOMapper;
use Silecust\WebShop\Form\MasterData\Category\Image\DTO\CategoryImageDTO;
use Silecust\WebShop\Repository\CategoryImageRepository;
use Silecust\WebShop\Repository\CategoryRepository;

readonly class CategoryImageDTOMapper
{

    public function __construct(private FileDTOMapper $fileDTOMapper,
        private CategoryRepository                    $categoryRepository,
        private CategoryImageRepository               $categoryImageRepository
    ) {
    }

    public function mapDtoToEntityForCreate(CategoryImageDTO $categoryImageDTO): CategoryImage
    {

        $category = $this->categoryRepository->find($categoryImageDTO->categoryId);

        $file = $this->fileDTOMapper->mapToFileEntityForCreate($categoryImageDTO->fileDTO);

        return $this->categoryImageRepository->create($category, $file);

    }

    public function mapDtoToEntityForEdit(CategoryImageDTO $fileImageDTO,
        CategoryImage $categoryImage
    ): CategoryImage {


        $file = $this->fileDTOMapper->mapToFileEntityForEdit(
            $fileImageDTO->fileDTO,
            $categoryImage->getFile()
        );

        $categoryImage->setFile($file);

        return $categoryImage;


    }

    public function mapEntityToDtoForEdit(CategoryImage $categoryImage): CategoryImageDTO
    {
        $categoryImageDTO = new CategoryImageDTO();


        $categoryImageDTO->id = $categoryImage->getId();
        $categoryImageDTO->fileDTO = $this->fileDTOMapper->mapEntityToFileDto($categoryImage->getFile());


        return $categoryImageDTO;

    }

}