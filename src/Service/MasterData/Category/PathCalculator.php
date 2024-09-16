<?php

namespace App\Service\MasterData\Category;

use App\Entity\Category;

class PathCalculator
{

    public function calculate(Category $category): string
    {
        return $category->getParent() == null ?
            "/{$category->getId()}" : "{$category->getParent()->getPath()}/{$category->getId()}";
    }
}