<?php

namespace Silecust\WebShop\Service\Common\List;

class ListGridConfig
{
    private  string $listTitle;
    private array $gridColumns = [];
    private CreateButtonConfig $createButtonConfig;

    public function getListTitle(): string
    {
        return $this->listTitle;
    }

    public function setListTitle(string $listTitle): void
    {
        $this->listTitle = $listTitle;
    }

    public function getGridColumns(): array
    {
        return $this->gridColumns;
    }

    public function setGridColumns(array $gridColumns): void
    {
        $this->gridColumns = $gridColumns;
    }

    public function getCreateButtonConfig(): CreateButtonConfig
    {
        return $this->createButtonConfig;
    }

    public function setCreateButtonConfig(CreateButtonConfig $createButtonConfig): void
    {
        $this->createButtonConfig = $createButtonConfig;
    }


}