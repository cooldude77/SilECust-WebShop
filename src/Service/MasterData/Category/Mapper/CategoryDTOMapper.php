<?php

namespace Silecust\WebShop\Service\MasterData\Category\Mapper;

use Silecust\WebShop\Entity\Category;
use Silecust\WebShop\Form\MasterData\Category\DTO\CategoryDTO;
use Silecust\WebShop\Repository\CategoryRepository;

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

        // the path is set to random because the id of the category is not known before persisting
        // it will be set later in the event.
        $category->setPath(md5(uniqid()));


        return $category;
    }

    public function mapToEntityForEdit(CategoryDTO $categoryDTO): Category
    {

        $category = $this->categoryRepository->findOneBy(['id'=>$categoryDTO->id]);

        $category->setName($categoryDTO->name);
        $category->setDescription($categoryDTO->description);

        $category->setParent($this->categoryRepository->findOneBy(['id' => $categoryDTO->parent]));

        // Note : the path will be set in lifestylecallback

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