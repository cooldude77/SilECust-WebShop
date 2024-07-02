<?php

namespace App\Service\Common\Image;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SystemImage
{


    public function __construct(#[Autowire(param: 'kernel.project_dir')]
    private readonly string $projectDirectory
    ) {
    }

    public function getNoImageForProductPath(): string
    {
        return $this->projectDirectory . '/public/assets/images/' . 'no_image.png';

    }

    public function getLogoPath():string
    {
        return $this->projectDirectory . '/public/assets/images/' . 'logo.png';
    }
}