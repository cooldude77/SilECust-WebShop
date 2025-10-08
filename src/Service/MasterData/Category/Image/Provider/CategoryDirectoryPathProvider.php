<?php

namespace Silecust\WebShop\Service\MasterData\Category\Image\Provider;

use Silecust\WebShop\Service\Common\File\Base\AbstractFileDirectoryPathProvider;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 *  Directory Structure:
 *
 *  Category: Base Kernel Dir/public/files/Category/{id}/{filename.extension}
 */
class CategoryDirectoryPathProvider extends AbstractFileDirectoryPathProvider
{


    public function __construct(
        #[Autowire(param: 'kernel.project_dir')] string $projectDir,
        #[Autowire(param: 'file_storage_path')] string  $fileStoragePathFromParameter,
        #[Autowire(param: 'uploads_segment')] string    $uploadsSegment,


    )
    {
        parent::__construct($projectDir, $fileStoragePathFromParameter, $uploadsSegment, '/category');
    }


}