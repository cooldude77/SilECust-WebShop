<?php

namespace Silecust\WebShop\Service\Admin\Employee\FrameWork\Head;

use Doctrine\ORM\EntityManagerInterface;
use Silecust\WebShop\Exception\Admin\Common\FunctionNotMappedToAnyEntity;
use Silecust\WebShop\Exception\Admin\Employee\Common\TitleNotFoundForAdminRouteObject;
use Silecust\WebShop\Service\Admin\Employee\Route\AdminRouteObject;
use Silecust\WebShop\Service\Admin\Employee\Route\FunctionToEntityMapper;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\UnicodeString;

readonly class PageTitleProvider
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