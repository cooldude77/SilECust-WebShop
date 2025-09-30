<?php

namespace Silecust\WebShop\Form\Common\File\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class FileDTO
{
    #[Assert\NotNull]

    public ?string $name = null;

    public ?string $type = null;

    #[Assert\NotNull(message: "Please provide a file name for your file")]

    public ?string $yourFileName = null;

    #[Assert\NotNull]

    public UploadedFile $uploadedFile;

    #[Assert\GreaterThan(0, message: "Please provide a file name for your file", groups: ['edit'])]

    public ?int $id = 0;

}