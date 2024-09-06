<?php

namespace App\Service\MasterData\Product\Filter\Provider;

use App\Entity\Category;

interface ProductFilterProviderInterface
{

    public function getList(Category $category);
}