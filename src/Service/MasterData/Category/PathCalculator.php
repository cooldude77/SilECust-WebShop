<?php

namespace Silecust\WebShop\Service\MasterData\Category;

use Silecust\WebShop\Entity\Category;

class PathCalculator
{

    public function calculate(Category $category): string
    {
        return $category->getParent() == null ?
            "/{$category->getId()}" : "{$category->getParent()->getPath()}/{$category->getId()}";
    }
}