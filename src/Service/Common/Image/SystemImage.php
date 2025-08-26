<?php

namespace Silecust\WebShop\Service\Common\Image;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class SystemImage
{


    public function __construct(#[Autowire(param: 'kernel.project_dir')]
    private string $projectDirectory
    ) {
    }

    public function getNoImageForProductPath(): string
    {
        return $this->projectDirectory . '/vendor/silecust/web-shop/src/System/Images/' . 'no_image.png';

    }

    public function getLogoPath():string
    {
        return $this->projectDirectory . '/vendor/silecust/web-shop/src/System/Images/' . 'silecust_logo.png';
    }

}