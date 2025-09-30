<?php

namespace Silecust\WebShop\Form\MasterData\Product\Image\DTO;

use Silecust\WebShop\Form\Common\File\DTO\FileDTO;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO should contain no objects of entity type
 */
class ProductImageDTO
{

    #[Assert\NotNull]
    public ?FileDTO $fileDTO = null;

    /**
     * @var int
     */
    #[Assert\GreaterThan(
        value: 0, message: "Product Id cannot be null", groups: ['create'])]
    public int $productId = 0;
    /**
     * @var int
     */
    #[Assert\GreaterThan(
        value: 0, message: "Product Image Id cannot be null", groups: ['edit'])]
    public int $id = 0;

    public int $minWidth = 0;
    public int $minHeight = 0;

    public function __construct()
    {
        $this->fileDTO = new FileDTO();
    }


    public function getFileName(): string
    {
        return $this->fileDTO->name;
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->fileDTO->uploadedFile;
    }


}