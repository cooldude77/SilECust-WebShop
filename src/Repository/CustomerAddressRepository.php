<?php

namespace Silecust\WebShop\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Silecust\WebShop\Entity\Customer;
use Silecust\WebShop\Entity\CustomerAddress;

/**
 * @extends ServiceEntityRepository<CustomerAddress>
 *
 * @method CustomerAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerAddress[]    findAll()
 * @method CustomerAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddress::class);
    }

    //    /**
    //     * @return CustomerAddress[] Returns an array of CustomerAddress objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerAddress
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function create(Customer $customer): CustomerAddress
    {
        $address = new CustomerAddress();
        $address->setCustomer($customer);
        return $address;
    }

    public function getQueryForSelectByCustomer(Customer $customer): Query
    {
        $dql = "SELECT ca FROM Silecust\WebShop\Entity\CustomerAddress ca where ca.customer=:customer";
        return $this->getEntityManager()->createQuery($dql)->setParameter('customer', $customer);

    }

    public function remove(CustomerAddress $customerAddress): void
    {

        $this->getEntityManager()->remove($customerAddress);
        $this->getEntityManager()->flush();

    }
}
