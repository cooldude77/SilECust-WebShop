<?php

namespace App\Controller\Common\File;

use App\Service\Common\Image\SystemImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

class SystemImageController extends AbstractController
{


    #[Route('system/image/logo', name: 'system_image_load_logo')]
    public function getLogo(SystemImage $systemImage): BinaryFileResponse
    {
        $path = $systemImage->getLogoPath();
        return new BinaryFileResponse($path);
    }
}