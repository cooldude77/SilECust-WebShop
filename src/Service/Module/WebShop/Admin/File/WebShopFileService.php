<?php

namespace Silecust\WebShop\Service\Module\WebShop\Admin\File;

use Silecust\WebShop\Entity\WebShop;
use Silecust\WebShop\Entity\WebShopFile;
use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileDTO;
use Silecust\WebShop\Repository\WebShopFileRepository;
use Silecust\WebShop\Repository\WebShopRepository;
use Silecust\WebShop\Service\Common\File\FilePhysicalOperation;
use Symfony\Component\HttpFoundation\File\File;

class WebShopFileService
{
    private WebShopFileDirectoryPathNamer $webShopFileDirectoryPathNamer;
    private FilePhysicalOperation $fileService;
    private WebShopFileRepository $webShopFileRepository;
    private WebShopRepository $webShopRepository;

    public function __construct(WebShopFileRepository $webShopFileRepository, WebShopRepository $webShopRepository, WebShopFileDirectoryPathNamer $webShopFileDirectoryPathNamer, FilePhysicalOperation $fileService)
    {

        $this->webShopFileDirectoryPathNamer = $webShopFileDirectoryPathNamer;
        $this->fileService = $fileService;
        $this->webShopFileRepository = $webShopFileRepository;
        $this->webShopRepository = $webShopRepository;
    }

    public function mapFormDTO(WebShopFileDTO $webShopFileDTO): WebShopFile
    {
        /** @var WebShop $webShop */
        $webShop = $this->webShopRepository->findOneBy(['id' => $webShopFileDTO->webShopId]);

        return $this->webShopFileRepository->create($this->fileService->mapDTOToEntity($webShopFileDTO->fileFormDTO), $webShop);

    }

    public function moveFile(WebShopFileDTO $webShopFileDTO): File
    {

        return $this->fileService->createOrReplaceFile($this->webShopFileDirectoryPathNamer,
            $webShopFileDTO->fileFormDTO->uploadedFile,
            $webShopFileDTO->fileFormDTO->name,
            ['webShopId' => $webShopFileDTO->webShopId]);
    }


}