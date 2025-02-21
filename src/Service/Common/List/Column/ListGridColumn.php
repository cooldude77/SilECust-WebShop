<?php

namespace Silecust\WebShop\Service\Common\List\Column;

class ListGridColumn
{
    private string $label;
    private ?string $propertyName;
    private string $action;

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function setPropertyName(?string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }



}