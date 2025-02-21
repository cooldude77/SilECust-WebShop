<?php

namespace Silecust\WebShop\Service\Module\WebShop\Admin\File;

use Silecust\WebShop\Entity\WebShopImageFile;
use Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO\WebShopFileImageDTO;
use Silecust\WebShop\Repository\WebShopImageFileRepository;
use Silecust\WebShop\Repository\WebShopImageTypeRepository;
use Symfony\Component\HttpFoundation\File\File;

class WebShopFileImageService
{


    private WebShopImageFileRepository $webShopImageFileRepository;
    private WebShopImageTypeRepository $webShopImageTypeRepository;
    private WebShopFileService $webShopFileService;

    public function __construct(
        WebShopImageFileRepository $webShopImageFileRepository,
        WebShopImageTypeRepository $webShopImageTypeRepository,
        WebShopFileService         $webShopFileService)
    {


        $this->webShopImageFileRepository = $webShopImageFileRepository;
        $this->webShopImageTypeRepository = $webShopImageTypeRepository;
        $this->webShopFileService = $webShopFileService;
    }

    public function mapFormDTO(WebShopFileImageDTO $webShopFileImageDTO): WebShopImageFile
    {

        return $this->webShopImageFileRepository->create(
            $this->webShopFileService->mapFormDTO($webShopFileImageDTO->webShopFileDTO),
        $this->webShopImageTypeRepository->findOneBy(['type'=>$webShopFileImageDTO->imageType]));

    }

    public function moveFile(WebShopFileImageDTO $webShopFileImageDTO): File
    {
        return $this->webShopFileService->moveFile($webShopFileImageDTO->webShopFileDTO);
    }


}