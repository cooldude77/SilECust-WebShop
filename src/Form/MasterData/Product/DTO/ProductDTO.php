<?php

namespace Silecust\WebShop\Form\MasterData\Product\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{

    /**
     * @var string|null
     */
    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\_\s]/',
        message: 'Only characters and numbers are allowed',
        match: true
    )]
    public ?string $name = null;

    #[Assert\Length(
        min: 1,
        max: 255,
        maxMessage: 'Length cannot exceed 255'
    )]
    public ?string $description = null;

    #[Assert\GreaterThan(
        value: 0,
        groups: ['edit']
    )]
    public ?int $id = 0;
    public bool $active;

    #[Assert\GreaterThan(
        value: 0
    )]
    #[Assert\NotNull]
    public ?int $categoryId = 0;
}