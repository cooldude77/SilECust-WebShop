<?php

namespace App\EventListener\MasterData\Category;

use App\Entity\Category;
use App\Service\MasterData\Category\PathCalculator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Category::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Category::class)]
class CategorySaveAndUpdate
{
    /**
     * @param PathCalculator $calculator
     */
    public function __construct(private readonly PathCalculator $calculator)
    {
    }

    public function postPersist(Category $category, PostPersistEventArgs $postPersistEventArgs): void
    {

        $path = $this->calculator->calculate($category);
        $category->setPath($path);

        $entityManager = $postPersistEventArgs->getObjectManager();

        $entityManager->persist($category);
        $entityManager->flush();
    }

    public function postUpdate(Category $category, PostUpdateEventArgs $postUpdateEventArgs): void
    {

        $path = $this->calculator->calculate($category);
        $category->setPath($path);

        $entityManager = $postUpdateEventArgs->getObjectManager();

        $entityManager->persist($category);
        $entityManager->flush();
    }
}