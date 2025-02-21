<?php

namespace Silecust\WebShop\Service\Common\File\Base;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

define('DS', DIRECTORY_SEPARATOR);

class AbstractFileDirectoryPathProvider
{
    /**
     * @var string
     */
    private string $projectDir;
    private string $baseFilePathSegment;

    private string $uploadsSegment ='/uploads';

    public function __construct(
        #[Autowire(param: 'kernel.project_dir')]
        string $projectDir,  #[Autowire(param: 'file_storage_path')]
    string $fileStoragePathFromParameter)
    {

        $this->projectDir = $projectDir;

        $this->baseFilePathSegment = $fileStoragePathFromParameter.$this->uploadsSegment;

    }

    private function getProjectDir(): string
    {
        return $this->projectDir;
    }

    /**
     * @return string
     * Provides complete directory path ( but not the file name )
     */
    protected function getPhysicalFilePathForFiles(): string
    {

        return $this->getProjectDir() . $this->getBaseFilePathSegment();
    }

    /**
     * @return string
     * Only provides the segment till uploads folder
     */
    public function getBaseFilePathSegment(): string
    {
        return $this->baseFilePathSegment;
    }


}