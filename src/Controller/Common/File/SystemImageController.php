<?php

namespace Silecust\WebShop\Controller\Common\File;

use Silecust\WebShop\Service\Common\Image\SystemImage;
use Silecust\Framework\Service\Component\Controller\EnhancedAbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

class SystemImageController extends EnhancedAbstractController
{


    #[Route('system/image/logo', name: 'system_image_load_logo')]
    public function getLogo(SystemImage $systemImage): BinaryFileResponse
    {
        $path = $systemImage->getLogoPath();
        return new BinaryFileResponse($path);
    }
}