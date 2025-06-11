<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Product;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

readonly class ProductListQueryBuilder
{

    public function __construct(private EntityManagerInterface $entityManager, private CategoryRepository $categoryRepository)
    {
    }

    public function getQuery(Request $request): Query
    {

        $builder = $this->buildBaseQuery($request);

        if ($request->query->get('sort_by') != null) {
            // order by has to be present and valid
            // or finally it will be descending

            if ($request->query->get('order') != null) if (in_array(strtoupper($request->query->get('order')), array('ASC', 'DESC'))) $orderBy = strtoupper($request->query->get('order')); else
                $orderBy = 'DESC';
        }
        if (!isset($orderBy)) $orderBy = 'ASC';

        if ($request->query->get('sort_by') == 'price') {
            $builder = $this->getBuilderForPriceQuery($builder, $orderBy);
        } else {
            $builder->orderBy('p.id', $orderBy);
        }

        $this->addSearchTermIfNeeded($builder, $request);
        return $builder->getQuery();
    }

    /**
     * @param Request $request
     * @return QueryBuilder
     */
    public function buildBaseQuery(Request $request): QueryBuilder
    {
        if ($request->query->get('category') != null) {
            $builder = $this->getBuilderFromCategory($request);
        } else {

            $builder = $this->getBuilderForAllProductQuery();
        }
        $builder->andWhere('p.isActive = true');

        return $builder;
    }

    /**
     * @param Request $request
     * @return QueryBuilder
     */
    public function getBuilderFromCategory(Request $request): QueryBuilder
    {
        $category = $this->categoryRepository->findOneBy(['name' => $request->query->get('category')]);

        // select all children
        return $this->entityManager->createQueryBuilder()->select('p')->from(Product::class, 'p')->join('p.category', 'c')->where('c.path like :path')->setParameter('path', "{$category->getPath()}%");
    }

    /**
     * @return QueryBuilder
     */
    public function getBuilderForAllProductQuery(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()->select('p')->from(Product::class, 'p');
    }

    /**
     * @param QueryBuilder $builder
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function getBuilderForPriceQuery(QueryBuilder $builder, string $orderBy): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()->select('pbp', 'p')
            ->from(PriceProductBase::class, 'pbp')->join('pbp.product', 'p')
            // Important: DQL strips parameters so this will throw error
            // if there are parameters attached to builder
            ->where($builder->expr()->in('p', $builder->getDQL()))
            // do this to add back the parameters that we lost
            ->setParameters($builder->getParameters())->orderBy('pbp.price', $orderBy);
    }

    private function addSearchTermIfNeeded(QueryBuilder $builder, Request $request): void
    {
        if ($request->get('searchTerm') != null) {
            $builder->where($builder->expr()->like('p.name', ':searchTerm'))->orWhere($builder->expr()->like('p.description', ':searchTerm'))->orWhere(

                $builder->expr()->like('p.longDescription', ':searchTerm'))->setParameter('searchTerm', '%' . $request->get('searchTerm') . '%');
        }
    }

}