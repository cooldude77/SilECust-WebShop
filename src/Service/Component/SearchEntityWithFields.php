<?php

namespace Silecust\WebShop\Service\Component;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;
use Silecust\WebShop\Repository\ProductRepository;
use Silecust\WebShop\Service\Component\Database\Repository\SearchableRepository;
use Silecust\WebShop\Service\Component\UI\Search\SearchEntityInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchEntityWithFields implements SearchEntityInterface
{


    public function searchByTerm(string $searchTerm, array $fieldNames): Criteria
    {
        $criteria = Criteria::create();
        foreach ($fieldNames as $fieldName)
            $criteria->orWhere(Criteria::expr()->contains($fieldName, $searchTerm));

        return $criteria;
    }

    /**
     * @param Request $request
     * @param SearchEntityInterface $searchEntity
     * @param ProductRepository $productRepository
     * @return Query
     * @throws QueryException
     */
    public function getQueryForSelect(Request $request, SearchableRepository $repository, $fields): Query
    {
        if ($request->query->get('searchTerm') != null)
            $searchCriteria = $this->searchByTerm($request->query->get('searchTerm'), ['name', 'description']);

        $query = $repository->getQueryForSelect($searchCriteria ?? null);
        return $query;
    }
}