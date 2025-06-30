<?php

namespace Silecust\WebShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Query\AST\DeleteClause;
use Silecust\WebShop\Repository\OrderItemRepository;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[UniqueConstraint(name: "UNIQ_ORDER_HEADER_PRODUCT", columns: ["order_header_id", "product_id"])]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,onDelete: 'CASCADE')]
    private ?OrderHeader $orderHeader = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true,onDelete: 'SET NULL')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private mixed $productInJson = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderHeader(): ?OrderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(?OrderHeader $orderHeader): static
    {
        $this->orderHeader = $orderHeader;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProductInJson(): mixed
    {
        return $this->productInJson;
    }

    public function setProductInJson(mixed $productInJson): void
    {
        $this->productInJson = $productInJson;
    }
    

}
