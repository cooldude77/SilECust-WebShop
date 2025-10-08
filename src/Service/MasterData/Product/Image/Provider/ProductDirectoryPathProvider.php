<?php

namespace Silecust\WebShop\Service\MasterData\Product\Image\Provider;

use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 *  Directory Structure:
 *
 *  Product: Base Kernel Dir/public/files/Products/{id}/{filename.extension}
 */
class ProductDirectoryPathProvider extends AbstractFileDirectoryPathProvider
{


    public function __construct(
        #[Autowire(param: 'kernel.project_dir')] string $projectDir,
        #[Autowire(param: 'file_storage_path')] string  $fileStoragePathFromParameter,
        #[Autowire(param: 'uploads_segment')] string    $uploadsSegment,

    )
    {
        parent::__construct($projectDir, $fileStoragePathFromParameter, $uploadsSegment, '/product');
    }

}