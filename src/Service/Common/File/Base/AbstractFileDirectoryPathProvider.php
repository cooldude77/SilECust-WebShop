<?php

namespace Silecust\WebShop\Service\Common\File\Base;

use Silecust\WebShop\Service\Common\File\Provider\Interfaces\DirectoryPathProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

define('DS', DIRECTORY_SEPARATOR);

#[Exclude]
class AbstractFileDirectoryPathProvider implements DirectoryPathProviderInterface
{


    public function __construct(
        private readonly string $projectDir,
        private readonly string $fileStoragePathFromParameter,
        private readonly string $uploadsSegment,
        private readonly string $componentLevelPathSegment)
    {

    }

    public function getBaseFolderPath(): string
    {
        return $this->getPhysicalFilePathForFiles() . $this->componentLevelPathSegment;
    }

    /**
     * @return string
     * Provides complete directory path ( but not the file name )
     */
    protected function getPhysicalFilePathForFiles(): string
    {

        return $this->getProjectDir() . $this->getPathToUploadsSegment() . $this->componentLevelPathSegment;
    }

    private function getProjectDir(): string
    {
        return $this->projectDir;
    }

    /**
     * @return string
     * Only provides the segment till uploads folder
     */
    public function getPathToUploadsSegment(): string
    {
        return $this->fileStoragePathFromParameter . $this->uploadsSegment;
    }

}