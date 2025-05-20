<?php

namespace Silecust\WebShop\Service\Admin\Employee\Settings\System;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Silecust\WebShop\Form\Common\File\Mapper\FileDTOMapper;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Silecust\WebShop\Service\Common\File\Provider\FileDirectoryPathProvider;

class Settings
{

    public function __construct(
        private readonly EntityManagerInterface    $entityManager,
        private readonly FileDTOMapper             $fileDTOMapper,
        private readonly FileDirectoryPathProvider $fileDirectoryPathProvider,
        private readonly FilePhysicalOperation     $filePhysicalOperation)
    {
    }

    // TODO: Replace / Delete  image
    /**
     * @param FileDTO $fileDTO
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveLogo(FileDTO $fileDTO): void
    {
        $file = $this->fileDTOMapper->mapToFileEntityForCreate($fileDTO);

        $this->filePhysicalOperation->createOrReplaceFile($fileDTO->uploadedFile, $file->getName(),
            $this->fileDirectoryPathProvider->getBaseFolderPath());

        $this->entityManager->persist($file);
        $this->entityManager->flush();

    }
}