<?php

namespace App\Form\MasterData\Category\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
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
        pattern: '/^[A-Za-z0-9\-\_]$/',
        message: 'Only characters and numbers are allowed',
        match: true
    )]
    public ?string $name = null;

    /**
     * @var string|null
     */

    #[Assert\Length(
        min: 1,
        max: 1000,
        maxMessage: 'Length cannot exceed 1000'
    )]

    public ?string $description = null;

    public ?string $parent = null;

    public ?int $id = -1;
}