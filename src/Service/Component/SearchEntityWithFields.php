<?php

namespace Silecust\WebShop\Service\Component;

use Doctrine\Common\Collections\Criteria;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;

class SearchEntityWithFields implements SearchEntityInterface
{


    public function searchByTerm(string $searchTerm, array $fieldNames): Criteria
    {
        $criteria = Criteria::create();
        foreach ($fieldNames as $fieldName)
            $criteria->orWhere(Criteria::expr()->contains($fieldName, $searchTerm));

        return $criteria;
    }
}