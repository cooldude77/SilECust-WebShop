<?php

namespace Silecust\WebShop\Service\MasterData\Product;

use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Doctrine\Common\Collections\Criteria;

class ProductSearch
{

    public function searchByTerm(string $searchTerm, array $fieldNames): Criteria
    {
        return Criteria::create()->andWhere(
            Criteria::expr()->contains('name', $searchTerm));
    }
}