<?php

namespace App\Entity;

use App\Repository\WebShopFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WebShopFileRepository::class)]
class WebShopFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $file = null;
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WebShop $webShop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebShop(): ?WebShop
    {
        return $this->webShop;
    }

    public function setWebShop(?WebShop $webShop): static
    {
        $this->webShop = $webShop;

        return $this;
    }
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): static
    {
        $this->file = $file;

        return $this;
    }

}
