<?php

namespace Silecust\WebShop\Service\Common\List;

class CreateButtonConfig
{

    private string $function;
    private  string $id;
    private string $anchorText;

    public function getFunction(): string
    {
        return $this->function;
    }

    public function setFunction(string $function): void
    {
        $this->function = $function;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAnchorText(): string
    {
        return $this->anchorText;
    }

    public function setAnchorText(string $anchorText): void
    {
        $this->anchorText = $anchorText;
    }



}