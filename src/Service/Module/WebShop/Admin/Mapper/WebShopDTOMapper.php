<?php

namespace Silecust\WebShop\Service\Module\WebShop\Admin\Mapper;


use Silecust\WebShop\Entity\WebShop;
use Silecust\WebShop\Form\Module\WebShop\Admin\DTO\WebShopDTO;
use Silecust\WebShop\Repository\WebShopRepository;

class WebShopDTOMapper
{
    public function __construct(private WebShopRepository $webShopRepository)
    {
    }



    public function mapToEntityForCreate(WebShopDTO $webShopDTO): WebShop
    {
        $webShop = $this->webShopRepository->create();
        $webShop->setName($webShopDTO->name);
        $webShop->setDescription($webShopDTO->description);
        return $webShop;
    }

    public function mapToEntityForEdit(WebShopDTO $webShopDTO, WebShop $webShop
    ):WebShop {

        $webShop->setName($webShopDTO->name);
        $webShop->setDescription($webShopDTO->description);
        return $webShop;

    }
}