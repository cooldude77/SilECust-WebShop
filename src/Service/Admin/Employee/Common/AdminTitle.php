<?php

namespace App\Service\Admin\Employee\Common;

use App\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use App\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use App\Service\Admin\Employee\FrameWork\AdminRouteObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\UnicodeString;

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
        switch ($adminRouteObject->getType()) {
            case 'list':

              $string =   (new UnicodeString(
                  (new EnglishInflector())->pluralize($adminRouteObject->getFunction())[0])
              )->camel()->title();

                return "$string List";
            case 'create':
                return "Create {$adminRouteObject->getFunction()}";
            case 'edit':
                return "Edit {$repo->find($adminRouteObject->getId())->getName()}";
            case 'display':
                return "Display  {$repo->find($adminRouteObject->getId())->getName()}";
        }

        (new TitleNotFoundForAdminRouteObject())->setAdminRouteObject($adminRouteObject);

        throw new TitleNotFoundForAdminRouteObject();
    }
}