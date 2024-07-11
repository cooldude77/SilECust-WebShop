<?php

namespace App\Service\Admin\Employee\Common;

use App\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use App\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use App\Service\Admin\Employee\FrameWork\AdminRouteObject;
use Doctrine\ORM\EntityManagerInterface;

readonly class AdminTitle
{
    public function __construct(private EntityManagerInterface $entityManager,
        private readonly FunctionToEntityMapper $functionToEntityMapper
    ) {
    }

    /**
     * @throws FunctionNotMappedToAnyEntity
     * @throws TitleNotFoundForAdminRouteObject
     */
    public function getTitle(AdminRouteObject $adminRouteObject): string
    {
        $class = $this->functionToEntityMapper->map($adminRouteObject->getFunction());
        $repo = $this->entityManager->getRepository($class);
        switch ($adminRouteObject->getFunction()) {
            case 'list':
                return 'Categories List';
            case 'create':
                return 'Create category';
            case 'edit':
                return 'Edit ' . $repo->find($adminRouteObject->getId())->getName();
            case 'display':
                return 'Display ' . $repo->find($adminRouteObject->getId())->getName();
        }

        (new TitleNotFoundForAdminRouteObject())->setAdminRouteObject($adminRouteObject);

        throw new TitleNotFoundForAdminRouteObject();
    }
}