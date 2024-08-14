<?php

namespace App\Tests\Utility;

use Doctrine\ORM\EntityManagerInterface;

trait FindByCriteria
{

    public function findOneBy(string $entityName, array $criteria)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository($entityName);

        return $repo->findOneBy($criteria);


    }

    public function save(mixed $entity): mixed
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $em->persist($em);
        $em->flush();

        return $entity;
    }
}