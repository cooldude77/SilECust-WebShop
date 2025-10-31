<?php

namespace Silecust\WebShop\Service\Module\WebShop\External\Product;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Silecust\WebShop\Entity\PriceProductBase;
use Silecust\WebShop\Entity\Product;
use Silecust\WebShop\Exception\Module\WebShop\External\Product\InvalidCategorySearchFilter;
use Silecust\WebShop\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

readonly class ProductListQueryBuilder
{

    public function __construct(private EntityManagerInterface $entityManager,
                                private CategoryRepository     $categoryRepository)
    {
    }

    /**
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Product\InvalidCategorySearchFilter
     */
    public function getQuery(Request $request): Query
    {

        $builder = $this->buildBaseQuery($request);

        if ($request->query->get('sort_by') != null) {
            // order by has to be present and valid
            // or finally it will be descending

            if ($request->query->get('order') != null)
                if (in_array(strtoupper($request->query->get('order')), array('ASC', 'DESC')))
                    $orderBy = strtoupper($request->query->get('order'));
                else
                    $orderBy = 'DESC';
        }
        if (!isset($orderBy))
            $orderBy = 'ASC';

        if ($request->query->get('sort_by') == 'price') {
            $builder = $this->getBuilderForPriceQuery($builder, $orderBy);
        } else {
            $builder->orderBy('p.id', $orderBy);
        }

        return $builder->getQuery();
    }

    /**
     * @param Request $request
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Product\InvalidCategorySearchFilter
     */
    public function buildBaseQuery(Request $request): QueryBuilder
    {
        if ($request->query->get('category') != null) {
            $builder = $this->getBuilderFromCategory($request);
        } else {

            $builder = $this->getBuilderForAllProductQuery();
        }
        $builder->andWhere('p.active = true');

        return $builder;
    }

    /**
     * @param Request $request
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Silecust\WebShop\Exception\Module\WebShop\External\Product\InvalidCategorySearchFilter
     */
    public function getBuilderFromCategory(Request $request): QueryBuilder
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('name', $request->query->get('category')))
            ->orWhere($criteria->expr()->eq('description', $request->query->get('category')));

        $category = $this->categoryRepository->matching($criteria)->first();

        if ($category == null)
            throw new InvalidCategorySearchFilter("Category :{$request->query->get('category')} ");

        // select all children
        return $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->join('p.category', 'c')
            ->where('c.path like :path')
            ->setParameter('path', "{$category->getPath()}%");
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBuilderForAllProductQuery(): QueryBuilder
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $builder
     * @param string $orderBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBuilderForPriceQuery(QueryBuilder $builder, string $orderBy): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->select('pbp', 'product')
            ->from(PriceProductBase::class, 'pbp')
            ->join('pbp.product', 'product')
            // Important: DQL strips parameters so this will throw error
            // if there are parameters attached to builder
            ->where($builder->expr()->in('product', $builder->getDQL()))
            // do this to add back the parameters that we lost
            ->setParameters($builder->getParameters())
            ->orderBy('pbp.price', $orderBy);
    }

}