<?php

namespace Silecust\WebShop\Service\Component\UI\Search;

use Doctrine\Common\Collections\Criteria;

interface SearchEntityInterface
{

    public function searchByTerm(string $searchTerm, array $fieldNames): Criteria;
}