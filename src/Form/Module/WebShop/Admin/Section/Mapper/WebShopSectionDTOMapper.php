<?php

namespace Silecust\WebShop\Form\Module\WebShop\Admin\Section\Mapper;

use Silecust\WebShop\Entity\WebShopSection;
use Silecust\WebShop\Form\Module\WebShop\Admin\Section\DTO\WebShopSectionDTO;
use Silecust\WebShop\Repository\WebShopHomeSectionRepository;
use Silecust\WebShop\Repository\WebShopRepository;

class WebShopSectionDTOMapper
{
    private WebShopRepository $webShopRepository;
    private WebShopHomeSectionRepository $webShopHomeSectionRepository;

    public function __construct(WebShopRepository $webShopRepository, WebShopHomeSectionRepository $webShopHomeSectionRepository)
    {
        $this->webShopRepository = $webShopRepository;
        $this->webShopHomeSectionRepository = $webShopHomeSectionRepository;
    }

    public function map(WebShopSectionDTO $webShopSectionDTO): WebShopSection
    {
        $webShop = $this->webShopRepository->findOneBy(['id' => $webShopSectionDTO->webShopId]);

        $webShopSection = $this->webShopHomeSectionRepository->create($webShop);
        $webShopSection->setName($webShopSectionDTO->name);
        $webShopSection->setDescription($webShopSectionDTO->description);
        $webShopSection->setSectionOrder($webShopSectionDTO->sectionOrder);

        return $webShopSection;
    }
}