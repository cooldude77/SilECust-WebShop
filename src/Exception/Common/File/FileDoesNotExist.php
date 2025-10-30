<?php

namespace Silecust\WebShop\Exception\Common\File;

class FileDoesNotExist extends \Exception
{

    /**
     * @param string $dir
     * @param string|null $fileName
     */
    public function __construct(string $dir, ?string $fileName)
    {
        parent::__construct("File $fileName does not exist at $dir");

    }
}