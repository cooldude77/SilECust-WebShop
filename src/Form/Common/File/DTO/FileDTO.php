<?php

namespace Silecust\WebShop\Form\Common\File\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDTO
{
    /** @var string
     * @Assert\NotBlank(message="Please enter name of file")
     */
    public ?string $name = null;
    public ?string $type = null;
    public ?string $yourFileName = null;

    /**
     * @var UploadedFile
     * @Assert\NotBlank 
     */
    public UploadedFile $uploadedFile;
    public ?int $id = -1;

}