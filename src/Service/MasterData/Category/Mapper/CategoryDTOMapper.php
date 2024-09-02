<?php

namespace App\Service\MasterData\Category\Mapper;

use App\Entity\Category;
use App\Form\MasterData\Category\DTO\CategoryDTO;
use App\Repository\CategoryRepository;

class CategoryDTOMapper
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {

        $this->categoryRepository = $categoryRepository;
    }

    public function mapToEntityForCreate(CategoryDTO $categoryDTO): Category
    {


        $category = $this->categoryRepository->create();

        $category->setName($categoryDTO->name);
        $category->setDescription($categoryDTO->description);

        $category->setParent($this->categoryRepository->findOneBy(['id' => $categoryDTO->parent]));

        return $category;
    }

    public function mapToEntityForEdit(CategoryDTO $categoryDTO): Category
    {

        $category = $this->categoryRepository->findOneBy(['id'=>$categoryDTO->id]);

        $category->setName($categoryDTO->name);
        $category->setDescription($categoryDTO->description);

        $category->setParent($this->categoryRepository->findOneBy(['id' => $categoryDTO->parent]));


        return $category;
    }

    public function mapToDtoFromEntity(?Category $category): CategoryDTO
    {
        $categoryDTO = new CategoryDTO();

        $categoryDTO->id = $category->getId();
        $categoryDTO->name = $category->getName();
        $categoryDTO->description = $category->getDescription();

        $categoryDTO->parent = $category->getParent()!=null??$category->getParent()->getId();
        return $categoryDTO;

    }


}