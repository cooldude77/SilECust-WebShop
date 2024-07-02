<?php

namespace App\Service\Component\Database;

use Doctrine\ORM\EntityManagerInterface;

class DatabaseOperations
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }

    public function persist(mixed $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function remove(mixed $item): void
    {
        $this->entityManager->remove($item);
    }

    public function clear()
    {
        $this->entityManager->clear();
    }

    public function save(mixed $entity): mixed
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return  $entity;
    }

}