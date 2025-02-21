<?php

namespace Silecust\WebShop\Form\Module\WebShop\Admin\File\DTO;

use Silecust\WebShop\Entity\WebShopFile;
use Silecust\WebShop\Entity\WebShopImageType;

class WebShopFileImageDTO
{

    public ?WebShopFileDTO $webShopFileDTO  = null;
    public ?string $imageType = null;

    public int $minWidth = 0;
    public int $minHeight= 0;

    public function __construct()
    {
        $this->webShopFileDTO = new WebShopFileDTO();
    }

    public function create():WebShopFileImageDTO
    {
        return new WebShopFileImageDTO();
    }


}