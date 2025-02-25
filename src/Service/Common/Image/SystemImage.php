<?php

namespace Silecust\WebShop\Service\Common\Image;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SystemImage
{


    public function __construct(#[Autowire(param: 'kernel.project_dir')]
    private readonly string $projectDirectory
    ) {
    }

    public function getNoImageForProductPath(): string
    {
        return $this->projectDirectory . '/vendor/silecust/web-shop/src/Config/Images/' . 'no_image.png';

    }

    public function getLogoPath():string
    {
        return $this->projectDirectory . '/vendor/silecust/web-shop/src/Config/Images/' . 'silecust_logo.png';
    }
}