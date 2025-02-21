<?php

namespace Silecust\WebShop\Exception\Component\UI;

class BaseTemplateNotFoundPanelMainException extends \Exception
{
    public function __construct(string $templateName)
    {
        parent::__construct("Template Name : $templateName");
    }
}