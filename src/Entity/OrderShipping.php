<?php

namespace Silecust\WebShop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Silecust\WebShop\Repository\OrderShippingRepository;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints\GreaterThan;

#[ORM\Entity(repositoryClass: OrderShippingRepository::class)]
class OrderShipping
{

    const string TOTAL_SHIPPING_VALUE = "TOTAL_SHIPPING_VALUE";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?OrderHeader $orderHeader = null;

    /** @var float|null Total Shipping charges */
    #[ORM\Column]
    #[GreaterThan(0)]
    private ?float $value = null;

    #[Ignore]
    #[ORM\Column(type: Types::JSON, nullable: false)]
    private mixed $shippingConditionsInJson = [];

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


    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getShippingConditionsInJson(): mixed
    {
        return $this->shippingConditionsInJson;
    }

    public function setShippingConditionsInJson(mixed $shippingConditionsInJson): static
    {
        $this->shippingConditionsInJson = $shippingConditionsInJson;

        return $this;
    }
}
