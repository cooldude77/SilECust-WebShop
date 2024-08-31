<?php

namespace App\Service\MasterData\Product;

use App\Service\Component\UI\Search\SearchEntityInterface;
use Doctrine\Common\Collections\Criteria;

class ProductSearch implements SearchEntityInterface
{

    public function searchByTerm(string $searchTerm): Criteria
    {
        return Criteria::create()->andWhere(
            Criteria::expr()->contains('name', $searchTerm));
    }
}